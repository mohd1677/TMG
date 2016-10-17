<?php

namespace TMG\Console\CommandBundle\Parser;

use DateTime;
use DateInterval;
use TMG\Api\ApiBundle\Entity\Property;
use TMG\Api\ApiBundle\Entity\Repository\ContractRepository;
use TMG\Api\ApiBundle\Entity\Repository\ReputationCustomerRepository;
use TMG\Api\ApiBundle\Entity\Reputation;
use TMG\Api\ApiBundle\Entity\ResolveSetting;
use TMG\Api\ApiBundle\Entity\ResolveSettingSite;
use TMG\Console\CommandBundle\BaseParser;
use TMG\Api\ApiBundle\Entity\ReputationData;
use TMG\Api\ApiBundle\Entity\ReputationSite;
use TMG\Api\ApiBundle\Entity\RateOurStayData;
use TMG\Api\ApiBundle\Entity\ReputationEmail;
use TMG\Api\ApiBundle\Entity\ReputationReview;
use TMG\Api\ApiBundle\Entity\ReputationSurvey;
use TMG\Api\ApiBundle\Entity\ReputationSource;
use TMG\Api\ApiBundle\Entity\ReputationQuestion;
use TMG\Api\ApiBundle\Entity\ReputationSiteData;
use TMG\Api\ApiBundle\Entity\ReputationCustomer;
use TMG\Api\ApiBundle\Entity\ReputationCategory;
use Symfony\Component\Console\Helper\ProgressBar;
use TMG\Api\ApiBundle\Entity\RateOurStaySubdomain;
use TMG\Api\ApiBundle\Entity\ReputationCompetitor;
use TMG\Api\ApiBundle\Entity\ReputationCompetitorData;
use TMG\Api\ApiBundle\Entity\Repository\ReputationEmailRepository;
use TMG\Api\ApiBundle\Entity\Repository\ReputationReviewRepository;

/**
 * Reputation Parser
 *
 * Parse asset data and fetch updates from Engage.
 */
class ReputationParser extends BaseParser
{
    /** @var ProgressBar */
    protected $progress;

    /** @var string Engage API token */
    protected $engageToken = 'd03d27773e6c9463aaa43c2865471385';

    /** @var string Base URL of engage API */
    protected $baseUrl = 'http://tmgapi.engage121.com/service/tmg_index.php?format=json&data=';

    /** @var int Timestamp of last API request */
    protected $engageApiTimestamp;

    /**
     * @param $reputationAccounts
     * @param DateTime $date
     * @return string
     */
    public function processAccounts($reputationAccounts, DateTime $date)
    {
        if (!count($reputationAccounts) > 0) {
            return 'No accounts to process';
        }

        $this->output->writeln(
            '<info>Updating '
            .$date->format('F, Y')
            .' information for '
            .count($reputationAccounts)
            .' accounts...</info>'
        );

        // Create a new progress bar.
        $this->progress = new ProgressBar($this->output, count($reputationAccounts));

        // Start the progress bar.
        $this->progress->start();

        foreach ($reputationAccounts as $reputationAccount) {
            // We need to merge the reputation back in so we can update it.
            /** @var Reputation $reputation */
            $reputation = $this->entityManager->merge($reputationAccount);
            // We'll also refresh the entity. This does two things:
            // 1) Somehow, it gets associations working again...
            // 2) It's main and intended effect, is that it wipes out any changes
            //    made to the entity locally.
            //
            // I'm not sure why I need to run this, but it's needed to make the
            // ->getAxNumber() method work when we are importing a single property.
            $this->entityManager->refresh($reputation);

            // Then we'll process each aspect of the reputation in turn.
            $this->processReportingData($reputation, $date);

            $this->processEmailData($reputation, $date);

            $this->processReviewData($reputation, $date);

            $this->processResponseData($reputation, $date);

            $this->processCompetitorData($reputation, $date);

            $this->calculateLifetimeTotals($reputation);

            // Flush one last time in case we missed anything.
            $this->entityManager->flush();

            // Then clear the entity manager to keep things clean. (Speed?)
            $this->entityManager->clear();

            // And advance the progress bar.
            $this->progress->advance();
        }

        // We generate the log message before finishing the progress bar.
        // This allows us to cheat and use the progress bar instead of having to
        // count processed accounts manually.
        $log = $this->progress->getProgress().' of '.$this->progress->getMaxSteps().' processed.';

        // finish(), then sets the progress bar to 100% complete.
        $this->progress->finish();

        return $log;
    }

    ############################ BEGIN REPORTING SECTION ###########################

    /**
     * Process lifetime reporting data
     *
     * @param  Reputation $reputation
     * @param  DateTime $date
     *
     * @return bool
     */
    private function processReportingData(Reputation $reputation, DateTime $date)
    {
        $reportingData = $this->getEngageData(
            '',
            $reputation->getProperty()->getAxNumber(),
            $date
        );

        if (!$reportingData) {
            // Either there is nothing to process or the API returned an error.
            $this->progress->clear();
            $this->output->writeln(
                "\n<error>No report data for ".$reputation->getProperty()->getAxNumber()."</error>"
            );
            $this->progress->display();

            return false;
        }

        $reputation
            ->setGuid($reportingData['guid'])
            ->setTripAdvisorRank($reportingData['TripAdvisor']);

        if (isset($reportingData['lifetime_external_reviews'])) {
            $lifetimeTotals = $reportingData['lifetime_external_reviews'];

            $positiveReviews = 0;

            // Key = the number of stars, and value = the number of reviews for that star rating.
            foreach ($lifetimeTotals['stars'] as $key => $value) {
                if ($key >= 4) {
                    $positiveReviews += $value;
                }
            }

            $reputation
                ->setExternalAverageRating($reportingData['avg_rating'])
                ->setExternalTotal($lifetimeTotals['totals'])
                ->setExternalPositive($positiveReviews)
                ->setExternalStars((array)$lifetimeTotals['stars']);

            $this->processSiteData($reputation, $lifetimeTotals['num_of_review_per_sites']);

            $this->processRateOurStayData($reputation, $reportingData['guid']);
        }

        return true;
    }

    /**
     * Process site data
     *
     * @param  Reputation $reputation
     * @param  array $siteData
     *
     * @return void
     */
    private function processSiteData(Reputation $reputation, $siteData)
    {
        // Key is site name and value is an array of data
        foreach ($siteData as $key => $value) {
            $reputationSite = $this->getReputationSite($key);

            $reputationSiteData = $this->entityManager->getRepository('ApiBundle:ReputationSiteData')
                ->findOneBy(
                    [
                        'site' => $reputationSite,
                        'reputation' => $reputation,
                        'lifetime' => true,
                    ]
                );

            // If we can't find an existing record, create one.
            if (!$reputationSiteData) {
                $reputationSiteData = new ReputationSiteData();
                $reputationSiteData
                    ->setReputation($reputation)
                    ->setSite($reputationSite)
                    ->setLifetime(true);

                $this->entityManager->persist($reputationSiteData);
            }

            // Update a couple fields regardless of whether the record is new or existing.
            $reputationSiteData
                ->setReviewCount($value['count'])
                ->setAverageRating($value['avg_rating']);

            // The TripAdvisor average is a special case.
            if ($key == 'TripAdvisor') {
                $reputation->setTripAdvisorRating($value['avg_rating']);
            }
        }
    }

    /**
     * Create or update RateOurStay.com sub-domain
     *
     * @param  Reputation $reputation
     * @param  string $guid Globally Unique Identifier provided by Engage
     *
     * @return void
     */
    private function processRateOurStayData(Reputation $reputation, $guid)
    {
        $rateOurStayDataRepo = $this->entityManager->getRepository('ApiBundle:RateOurStayData');

        $rateOurStayData = $rateOurStayDataRepo->findOneBy(['property' => $reputation->getProperty()]);

        // If we don't have one, we'll try to find a record using the GUID.
        if (!$rateOurStayData) {
            $rateOurStayData = $rateOurStayDataRepo->findOneBy(['guid' => $guid]);
        }

        // If we still don't have an existing record, we'll create a new one.
        if (!$rateOurStayData) {
            $rateOurStayData = new RateOurStayData();
            $rateOurStayData
                ->setProperty($reputation->getProperty())
                ->setEnabled(false);

            $this->entityManager->persist($rateOurStayData);
        }

        $rateOurStayData->setGuid($guid);

        if ($this->processSubdomains($rateOurStayData)) {
            $rateOurStayData->setEnabled(true);
        }

        $reputation->getProperty()->setRateOurStayData($rateOurStayData);
    }

    /**
     * Create a default sub-domain entry if one does not already exist.
     *
     * @param  RateOurStayData $rateOurStayData
     *
     * @return bool
     */
    private function processSubdomains(RateOurStayData $rateOurStayData)
    {
        /** @var Property $property */
        $property = $rateOurStayData->getProperty();

        if (!$property->getRateOurStayData() && !$property->getTripStayWinData()) {
            $subdomain = new RateOurStaySubdomain();
            $subdomain
                ->setRateOurStayData($rateOurStayData)
                ->setSubdomain($property->getHash());

            $this->entityManager->persist($subdomain);
        }

        return true;
    }

    ############################# END REPORTING SECTION ############################
    ############################## BEGIN EMAIL SECTION #############################

    /**
     * Process email data and customers
     *
     * @param  Reputation $reputation
     * @param  DateTime $date
     *
     * @return bool
     */
    private function processEmailData(Reputation $reputation, DateTime $date)
    {
        $emailData = $this->getEngageData(
            '_emailbydate',
            $reputation->getProperty()->getAxNumber(),
            $date
        );

        if (!$emailData) {
            // Either there is nothing to process or the API returned an error.
            $this->progress->clear();
            $this->output->writeln(
                "\n<error>No email data for ".$reputation->getProperty()->getAxNumber()."</error>"
            );
            $this->progress->display();

            return false;
        }

        $this->updateMonthlyEmailTotals($emailData, $reputation, $date);

        $this->importCustomers($emailData, $reputation);

        /** @var ReputationCustomerRepository $reputationCustomerRepository */
        $reputationCustomerRepository = $this->entityManager->getRepository('ApiBundle:ReputationCustomer');

        $lastUploadDate = $reputationCustomerRepository->getLastUploadDate($reputation);

        if ($lastUploadDate) {
            $reputation->setLastUpload($lastUploadDate['uploadDate']);
        }

        $this->entityManager->flush();

        return true;
    }

    /**
     * Update month email totals
     *
     * @param  array $emailData
     * @param  Reputation $reputation
     * @param  DateTime $date
     *
     * @return bool|ReputationEmail
     */
    private function updateMonthlyEmailTotals($emailData, Reputation $reputation, DateTime $date)
    {
        if (isset($emailData['surveys'])) {
            $totalsData = $emailData['surveys'];
        } else {
            $this->progress->clear();
            $this->output->writeln(
                "\n<error>No emails found for ".$reputation->getProperty()->getAxNumber()."</error>"
            );
            $this->progress->display();

            return false;
        }

        $totalsRecord = $this->entityManager->getRepository('ApiBundle:ReputationEmail')
            ->findOneBy(
                [
                    'yrmo' => $date->format('ym'),
                    'reputation' => $reputation,
                ]
            );

        if (!$totalsRecord) {
            $totalsRecord = new ReputationEmail();
            $totalsRecord
                ->setReputation($reputation)
                ->setYrmo($date);

            $this->entityManager->persist($totalsRecord);
        }

        $includedDate = new DateTime($emailData['to_date']);
        $includedDate->setTime(23, 59, 59);

        if ($totalsRecord->getLatestDateIncluded() < $includedDate) {
            $totalsRecord->setLatestDateIncluded($includedDate);
        }

        $totalsRecord
            ->setSent($totalsData['num_of_emails_sent'])
            ->setOpened($totalsData['num_of_emails_opened'])
            ->setYes($totalsData['num_of_email_clicks_yes'])
            ->setNo($totalsData['num_of_email_clicks_no']);

        $newRedirects = 0;

        if (isset($totalsData['redirect_url'])) {
            foreach ($totalsData['redirect_url'] as $value) {
                $newRedirects += $value;
            }
        }

        $totalsRecord->setRedirects($newRedirects);

        return $totalsRecord;
    }

    /**
     * Import customer/email data
     *
     * @param  array $emailData
     * @param  Reputation $reputation
     *
     * @return bool
     */
    private function importCustomers($emailData, Reputation $reputation)
    {
        if (isset($emailData['emails'])) {
            $customerData = $emailData['emails'];
        } else {
            $this->progress->clear();
            $this->output->writeln(
                "\n<error>No customers found for ".$reputation->getProperty()->getAxNumber()."</error>"
            );
            $this->progress->display();

            return false;
        }

        $customerRepository = $this->entityManager->getRepository('ApiBundle:ReputationCustomer');

        foreach ($customerData as $customer) {
            $checkoutDate = new DateTime($customer['checkout_date']);

            // First we'll look for an existing record based on Email ID.
            $customerRecord = $customerRepository->findOneBy(['emailId' => $customer['email_id']]);

            // If we don't find one, we'll look for one based on several other parameters.
            if (!$customerRecord) {
                $customerRecord = $customerRepository
                    ->findOneBy(
                        [
                            'email' => $customer['email'],
                            'checkoutDate' => $checkoutDate,
                            'reputation' => $reputation,
                        ]
                    );
            }

            // If we still didn't find one, we'll create a new one.
            if (!$customerRecord) {
                $customerRecord = new ReputationCustomer();
                $customerRecord
                    ->setReputation($reputation)
                    ->setEmail($customer['email'])
                    ->setCheckoutDate($checkoutDate);

                $this->entityManager->persist($customerRecord);
            }

            $customerRecord
                ->setFirstName($customer['first_name'])
                ->setLastName($customer['last_name'])
                ->setSentDate(new DateTime($customer['email_send_date']))
                ->setStatus($customer['status'])
                ->setRedirect($customer['redirect_url'])
                ->setEmailId($customer['email_id'])
                ->setOpened(($customer['num_open'] >= 1) ? 1 : 0)
                ->setYes($customer['click_yes'])
                ->setNo($customer['click_no'])
                ->setFollowUpNumberOpened($customer['follow_up_num_open'])
                ->setFollowUpClickYes($customer['follow_up_click_yes'])
                ->setUploadDate(new DateTime($customer['upload_date']))
                ->setFollowUpClickNo($customer['follow_up_click_no'])
                ->setFollowUpRedirectUrl($customer['follow_up_redirect_url'])
                ->setThankYouNumberOpened($customer['thank_you_email_num_open'])
                ->setThankYouClickTripadvisor($customer['thank_you_email_click_tripadvisor'])
                ->setThankYouClickGoogleplus($customer['thank_you_email_click_googleplus'])
                ->setThankYouClickSurvey($customer['thank_you_email_click_internal_survey'])
                ->setThankYouRedirectUrl($customer['thank_you_email_click_redirect_url']);

            if ($customer['follow_up_email_send_date']) {
                $customerRecord->setFollowUpEmailSendDate(new DateTime($customer['follow_up_email_send_date']));
            }

            if ($customer['thank_you_email_send_date']) {
                $customerRecord->setThankYouEmailSendDate(new DateTime($customer['thank_you_email_send_date']));
            }
        }

        return true;
    }

    ############################### END EMAIL SECTION ##############################
    ############################# BEGIN REVIEW SECTION #############################

    /**
     * Process review data
     *
     * @param  Reputation $reputation
     * @param  DateTime $date
     *
     * @return bool
     */
    private function processReviewData(Reputation $reputation, DateTime $date)
    {
        // The review endpoint is non-inclusive so we have to change the end date.
        $toDate = clone $date;
        $toDate->modify('first day of next month');

        $reviewData = $this->getEngageData(
            '_reviewbydate',
            $reputation->getProperty()->getAxNumber(),
            $date,
            $toDate
        );

        if (!$reviewData) {
            $this->progress->clear();
            $this->output->writeln(
                "\n<error>No review data for ".$reputation->getProperty()->getAxNumber()."</error>"
            );
            $this->progress->display();

            return false;
        }

        $this->importReviews($reviewData, $reputation);

        // We need to flush here, so we can calculate totals in the next step.
        $this->entityManager->flush();

        $this->updateMonthlyReviewTotals($reputation, $date);

        $this->updateMonthlySiteData($reputation, $date);

        // Flush the entity manager one more time to update totals data.
        $this->entityManager->flush();

        return true;
    }

    /**
     * Import reviews
     *
     * @param  array $reviewData
     * @param  Reputation $reputation
     *
     * @return bool
     */
    private function importReviews($reviewData, Reputation $reputation)
    {
        if (isset($reviewData['reviews'])) {
            $reviews = $reviewData['reviews'];
        } else {
            $this->progress->clear();
            $this->output->writeln(
                "\n<error>No reviews found for ".$reputation->getProperty()->getAxNumber()."</error>"
            );
            $this->progress->display();

            return false;
        }

        /** @var ReputationReviewRepository $reputationReviewRepository */
        $reputationReviewRepository = $this->entityManager->getRepository('ApiBundle:ReputationReview');

        foreach ($reviews as $review) {
            $reputationSite = $this->getReputationSite($review['site_name']);

            if (!$reputationSite) {
                $this->progress->clear();
                $this->output->writeln(
                    "\n<error>Unable to get review site for ".$review['site_name']."</error>"
                );
                $this->progress->display();

                continue;
            }

            $reviewRecord = $reputationReviewRepository
                ->findOneBy(
                    [
                        'engageId' => $review['guid'],
                        'reputation' => $reputation,
                    ]
                );

            if (!$reviewRecord) {
                $reviewRecord = new ReputationReview();
                $reviewRecord
                    ->setReputation($reputation)
                    ->setEngageId($review['guid'])
                    ->setSite($reputationSite)
                    ->setPostDate(new DateTime($review['post_date']))
                    ->setCritical(false);
                $this->entityManager->persist($reviewRecord);
            }

            $reviewRecord
                ->setUsername($review['user_name'])
                ->setContentShort($this->truncate($review['content_long'], 250))
                ->setContent($review['content_long'])
                ->setContentUrl($review['content_url'])
                ->setTone($review['tone'])
                ->setSentiment(($review['tone'] >= 3) ? 1 : 0);

            $resolvable = $this->isReviewResolvable($reviewRecord);
            $reviewRecord->setResolvable($resolvable)
                ->setProposable($resolvable);
        }

        return true;
    }

    /**
     * decide whether to mark review as resolvable
     *
     * @param ReputationReview $review
     *
     * @return bool
     */
    private function isReviewResolvable(ReputationReview $review)
    {
        $resolvable = false;

        /** @var ContractRepository $contractRepository */
        $contractRepository = $this->entityManager
            ->getRepository('ApiBundle:Contract');

        $activeContracts = $contractRepository
            ->findActiveContractsForDate(
                $review->getPostDate(),
                $contractRepository->resolveProductCodes,
                $review->getReputation()->getProperty()
            );

        if (!empty($activeContracts)) {
            if ($review->getRespondedAt() != null
                || $review->getReservedAt() != null
            ) {
                $resolvable = true;
            } elseif (!in_array($review->getContent(), ReputationReview::$genericContent)) {
                /** @var ResolveSetting $resolveSetting */
                $resolveSetting = $review->getReputation()->getProperty()->getResolveSetting();

                if ($resolveSetting instanceof ResolveSetting) {
                    $sites = $resolveSetting->getResolveSettingSites();

                    /** @var ResolveSettingSite $site */
                    foreach ($sites as $site) {
                        if ($site->getReputationSite() == $review->getSite()) {
                            if ($site->getEffectiveAt() <= $review->getPostDate()) {
                                $resolvable = true;
                            }
                            break;
                        }
                    }
                }
            }
        }

        return $resolvable;
    }

    /**
     * Update monthly totals for reviews
     *
     * @param  Reputation $reputation
     * @param  DateTime $date
     *
     * @return bool
     */
    private function updateMonthlyReviewTotals(Reputation $reputation, DateTime $date)
    {
        /** @var ReputationReviewRepository $reputationReviewRepository */
        $reputationReviewRepository = $this->entityManager->getRepository('ApiBundle:ReputationReview');

        $externalAvgRating = $reputationReviewRepository->getExternalAvgRating($reputation, $date);
        $externalAvgRating = number_format(round($externalAvgRating, 2), 2);

        $tripAdvisorAvgRating = $reputationReviewRepository->getTripAdvisorAvgRating($reputation, $date);
        $tripAdvisorAvgRating = number_format(round($tripAdvisorAvgRating, 2), 2);

        $reviews = $reputationReviewRepository
            ->findBy(
                array(
                    'reputation' => $reputation,
                    'yrmo' => $date->format('ym'),
                )
            );

        $positiveReviews = 0;
        $stars = [];

        /** @var ReputationReview $review */
        foreach ($reviews as $review) {
            if ($review->getTone() >= 4) {
                $positiveReviews++;
            }

            if (isset($stars[(int)round($review->getTone())])) {
                $stars[(int)round($review->getTone())]++;
            } else {
                $stars[(int)round($review->getTone())] = 1;
            }
        }

        // Sort the array keys in numerical order
        ksort($stars);

        $reputationDataRecord = $this->entityManager->getRepository('ApiBundle:ReputationData')
            ->findOneBy(
                [
                    'reputation' => $reputation,
                    'yrmo' => $date->format('ym'),
                ]
            );

        if (!$reputationDataRecord) {
            $reputationDataRecord = new ReputationData();
            $reputationDataRecord
                ->setReputation($reputation)
                ->setYrmo($date);

            $this->entityManager->persist($reputationDataRecord);
        }

        $reputationDataRecord
            ->setExternalAverageRating($externalAvgRating)
            ->setExternalTotal(count($reviews))
            ->setExternalPositive($positiveReviews)
            ->setTripAdvisorRating($tripAdvisorAvgRating)
            ->setExternalStars((array)$stars);

        return true;
    }

    /**
     * Update monthly totals for site data
     *
     * @param  Reputation $reputation
     * @param  DateTime $date
     *
     * @return bool
     */
    private function updateMonthlySiteData(Reputation $reputation, DateTime $date)
    {
        /** @var ReputationReviewRepository $reputationReviewRepository */
        $reputationReviewRepository = $this->entityManager->getRepository('ApiBundle:ReputationReview');

        $siteData = $reputationReviewRepository->getMonthlySiteData($reputation, $date);

        foreach ($siteData as $site) {
            $reputationSite = $this->getReputationSite($site['siteName']);

            $siteDataRecord = $this->entityManager->getRepository('ApiBundle:ReputationSiteData')
                ->findOneBy(
                    [
                        'reputation' => $reputation,
                        'site' => $reputationSite,
                        'yrmo' => $date->format('ym'),
                        'lifetime' => null,
                    ]
                );

            if (!$siteDataRecord) {
                $siteDataRecord = new ReputationSiteData();

                $siteDataRecord
                    ->setReputation($reputation)
                    ->setSite($reputationSite)
                    ->setYrmo($date->format('ym'));

                $this->entityManager->persist($siteDataRecord);
            }

            $siteDataRecord
                ->setReviewCount($site['siteCount'])
                ->setAverageRating(number_format(round($site['siteTone'], 2), 2));
        }

        return true;
    }

    ############################## END REVIEW SECTION ##############################
    ############################ BEGIN RESPONSE SECTION ############################

    /**
     * Process response data
     *
     * @param  Reputation $reputation
     * @param  DateTime $date
     *
     * @return bool
     */
    private function processResponseData(Reputation $reputation, DateTime $date)
    {
        $responseData = $this->getEngageData(
            '_responsebydate',
            $reputation->getProperty()->getAxNumber(),
            $date
        );

        if (!$responseData) {
            $this->progress->clear();
            $this->output->writeln(
                "\n<error>No response data for ".$reputation->getProperty()->getAxNumber()."</error>"
            );
            $this->progress->display();

            return false;
        }

        $this->importResponses($responseData['surveys'], $reputation, $date);

        $this->entityManager->flush();

        return true;
    }

    /**
     * Import customer responses
     *
     * @param  array $responseData
     * @param  Reputation $reputation
     * @param  DateTime $date
     *
     * @return bool
     */
    private function importResponses($responseData, Reputation $reputation, DateTime $date)
    {
        if (empty($responseData)) {
            $this->progress->clear();
            $this->output->writeln(
                "\n<error>No surveys found for ".$reputation->getProperty()->getAxNumber()."</error>"
            );
            $this->progress->display();

            return false;
        }

        foreach ($responseData as $response) {
            $checkoutDate = null;
            $sendDate = null;
            $responseDate = null;

            if ($response['checkout_date']) {
                $checkoutDate = DateTime::createFromFormat('m/d-Y', $response['checkout_date']);
            }

            if ($response['email_send_date']) {
                $sendDate = DateTime::createFromFormat('m/d/Y', $response['email_send_date']);
            }

            if ($response['survey_response_date']) {
                $responseDate = preg_replace('/ [ap]m$/i', '', $response['survey_response_date']);
                $responseDate = DateTime::createFromFormat('m/d/Y H:i', $responseDate);
            }

            $customerRepository = $this->entityManager->getRepository('ApiBundle:ReputationCustomer');

            $customer = $customerRepository->findOneBy(['emailId' => $response['email_id']]);

            if (!$customer) {
                $customer = $customerRepository->findOneBy(
                    [
                        'reputation' => $reputation,
                        'email' => $response['email'],
                        'checkoutDate' => $checkoutDate,
                    ]
                );
            }

            if (!$customer) {
                $customer = new ReputationCustomer();
                $customer
                    ->setReputation($reputation)
                    ->setEmail($response['email'])
                    ->setCheckoutDate($checkoutDate);

                $this->entityManager->persist($customer);
            }

            $opened = ($response['num_open_email'] >= 1);

            $customer
                ->setFirstName($response['first_name'])
                ->setLastName($response['last_name'])
                ->setSentDate($sendDate)
                ->setStatus('Completed')
                ->setEmailId($response['email_id'])
                ->setOpened((int)$opened)
                ->setYes($response['num_click_yes'])
                ->setNo($response['num_click_no'])
                ->setUploadDate($sendDate);

            $source = $this->getSource($response['source']);

            $survey = $this->getSurvey($reputation, $customer, $source);

            $survey
                ->setResponseDate($responseDate)
                ->setEmailType($response['email_type'])
                ->setOpen($opened)
                ->setYes($response['num_click_yes'])
                ->setNo($response['num_click_no'])
                ->setYrmo($date);

            $this->processSurveyResponse($response['survey_response'], $survey);
        }

        return true;
    }

    /**
     * Process survey responses
     *
     * @param  array $surveyResponse
     * @param  ReputationSurvey $survey
     *
     * @return bool
     */
    private function processSurveyResponse($surveyResponse, ReputationSurvey $survey)
    {
        $questionRepository = $this->entityManager->getRepository('ApiBundle:ReputationQuestion');

        foreach ($surveyResponse as $answer) {
            $question = $questionRepository
                ->findOneBy(
                    [
                        'survey' => $survey,
                        'question' => $answer['Question'],
                    ]
                );

            if (!$question) {
                $question = new ReputationQuestion();
                $question
                    ->setQuestion($answer['Question'])
                    ->setSurvey($survey);

                $this->entityManager->persist($question);
            }

            $category = null;

            if (isset($answer['Category'])) {
                /** @var ReputationCategory $category */
                $category = $this->getCategory($answer['Category']);
            }

            $longAnswer = null;
            $shortAnswer = null;

            if ($answer['Response']) {
                $longAnswer = $answer['Response'];
                $shortAnswer = $this->truncate($answer['Response'], 250);
            }

            if ($answer['Question'] == 'Over all rating') {
                // Get numeric rating from beginning of string.
                $overallRating = explode(',', $answer['Response']);

                $survey->setOverallRating($overallRating[0]);

                // Remove the rating value from the array.
                unset($overallRating[0]);

                // Re-assemble the answer to get the text that the respondent provided.
                $overallAnswer = implode(',', $overallRating);

                // Trim whitespace from beginning and end of answer.
                $longAnswer = trim($overallAnswer);

                // Truncate the response to 250 characters.
                $shortAnswer = $this->truncate(trim($overallAnswer), 250);
            }

            $question
                ->setLongAnswer($longAnswer)
                ->setShortAnswer($shortAnswer)
                ->setCategory($category);
        }

        return true;
    }

    /**
     * Get a category object or create one.
     *
     * @param  string $category
     *
     * @return ReputationCategory
     */
    private function getCategory($category)
    {
        $category = $this->entityManager->getRepository('ApiBundle:ReputationCategory')
            ->findOneBy(['name' => $category]);

        if (!$category) {
            $category = new ReputationCategory();
            $category->setName($category);

            $this->entityManager->persist($category);
        }

        return $category;
    }

    /**
     * Get source or create one
     *
     * @param  string $sourceName
     *
     * @return ReputationSource
     */
    private function getSource($sourceName)
    {
        /** @var ReputationSource $source */
        $source = $this->entityManager->getRepository('ApiBundle:ReputationSource')
            ->findOneBy(['name' => $sourceName]);

        if (!$source) {
            $source = new ReputationSource();
            $source->setName($sourceName);

            $this->entityManager->persist($source);
        }

        return $source;
    }

    /**
     * Get survey or create one
     *
     * @param  Reputation $reputation
     * @param  ReputationCustomer $customer
     * @param  ReputationSource $source
     *
     * @return ReputationSurvey
     */
    private function getSurvey(Reputation $reputation, ReputationCustomer $customer, ReputationSource $source)
    {
        $survey = $this->entityManager->getRepository('ApiBundle:ReputationSurvey')
            ->findOneBy(
                [
                    'reputation' => $reputation,
                    'customer' => $customer,
                    'source' => $source,
                ]
            );

        if (!$survey) {
            $survey = new ReputationSurvey();
            $survey
                ->setReputation($reputation)
                ->setCustomer($customer)
                ->setSource($source);

            $this->entityManager->persist($survey);
        }

        return $survey;
    }

    ############################# END RESPONSE SECTION #############################
    ########################## BEGIN COMPETITOR SECTION ############################

    /**
     * Coordination of processing competitor data
     *
     * @param  Reputation $reputation
     * @param  DateTime $date
     *
     * @return bool
     */
    private function processCompetitorData(Reputation $reputation, DateTime $date)
    {
        $competitorData = $this->getEngageData(
            '_competitors',
            $reputation->getProperty()->getAxNumber(),
            $date
        );

        if (!$competitorData) {
            $this->progress->clear();
            $this->output->writeln(
                "\n<error>No competitor data for ".$reputation->getProperty()->getAxNumber()."</error>"
            );
            $this->progress->display();

            return false;
        }

        $this->importCompetitors($competitorData, $reputation);

        $this->entityManager->flush();

        return true;
    }

    /**
     * @param $competitorData
     * @param Reputation $reputation
     * @return bool
     */
    private function importCompetitors($competitorData, Reputation $reputation)
    {
        if (empty($competitorData['competitors'])) {
            $this->progress->clear();
            $this->output->writeln(
                "\n<error>No competitor data for ".$reputation->getProperty()->getAxNumber()."</error>"
            );
            $this->progress->display();

            return false;
        }

        $competitors = $competitorData['competitors'];

        foreach ($competitors as $competitor => $competitorInfo) {
            $competitorRecord = $this->getCompetitor($reputation, $competitor);

            $competitorRecord
                ->setPhone($competitorInfo['phone']);

            foreach ($competitorInfo as $siteName => $competitorData) {
                if (is_array($competitorData)) {
                    $this->processCompetitorSiteData($competitorRecord, $siteName, $competitorData);

                    $this->updateLifetimeCompetitorInfo($competitorRecord);
                }
            }
        }

        return true;
    }

    /**
     * Get competitor or create one
     *
     * @param  Reputation $reputation
     * @param  string $competitor
     *
     * @return ReputationCompetitor
     */
    private function getCompetitor(Reputation $reputation, $competitor)
    {
        $competitorRecord = $this->entityManager->getRepository('ApiBundle:ReputationCompetitor')
            ->findOneBy(
                [
                    'reputation' => $reputation,
                    'name' => $competitor,
                ]
            );

        if (!$competitorRecord) {
            $competitorRecord = new ReputationCompetitor();
            $competitorRecord
                ->setReputation($reputation)
                ->setName($competitor);

            $this->entityManager->persist($competitorRecord);
        }

        return $competitorRecord;
    }

    private function processCompetitorSiteData(
        ReputationCompetitor $competitorRecord,
        $siteName,
        $competitorData
    ) {
        $site = $this->getReputationSite($siteName);

        $competitorDataRepo = $this->entityManager->getRepository('ApiBundle:ReputationCompetitorData');

        foreach ($competitorData['data'] as $month) {
            $yrmo = DateTime::createFromFormat('Y-m', $month['month']);

            /** @var DateTime $previousYrmo */
            $previousYrmo = clone $yrmo;
            $previousYrmo = $previousYrmo->sub(new DateInterval('P1M'));

            $previousRecord = $competitorDataRepo
                ->findOneBy(
                    [
                        'competitor' => $competitorRecord,
                        'site' => $site,
                        'yrmo' => $previousYrmo->format('ym'),
                    ]
                );

            $competitorDataRecord = $competitorDataRepo
                ->findOneBy(
                    [
                        'competitor' => $competitorRecord,
                        'site' => $site,
                        'yrmo' => $yrmo->format('ym'),
                    ]
                );

            if (!$competitorDataRecord) {
                $competitorDataRecord = new ReputationCompetitorData();
                $competitorDataRecord
                    ->setCompetitor($competitorRecord)
                    ->setSite($site)
                    ->setYrmo($yrmo);

                $this->entityManager->persist($competitorDataRecord);
            }

            $competitorDataRecord
                ->setUrl($competitorData['url'])
                ->setRssId($competitorData['rss_id'])
                ->setRating($month['overall_rating'])
                ->setReviews($month['num_reviews'])
                ->setMonthTotal($month['num_reviews']);


            if ($previousRecord) {
                $competitorDataRecord->setMonthTotal(
                    ($competitorDataRecord->getReviews() - $previousRecord->getReviews())
                );
            }

            if ($month['tripadvisor_city_rank']) {
                $cityRank = explode(' ', $month['tripadvisor_city_rank']);

                // Get the rank of the competitor as an integer from a string.
                // We need to find a substring beginning with '#'.
                $cityRank = array_filter(
                    $cityRank,
                    function ($v) {
                        return substr($v, 0, 1) === '#';
                    }
                );

                $cityRank = array_values($cityRank)[0];

                // Strip the leading "#" and any leading or trailing whitespace.
                $cityRank = trim($cityRank, '# ');

                $competitorDataRecord->setCityRank($cityRank);
            }
        }

        return true;
    }

    private function updateLifetimeCompetitorInfo(ReputationCompetitor $competitor)
    {
        $competitorData = $this->entityManager->getRepository('ApiBundle:ReputationCompetitorData')
            ->findOneBy(
                ['competitor' => $competitor],
                ['yrmo' => 'DESC']
            );

        if (!$competitorData) {
            $competitor
                ->setLifetimeReviews(0)
                ->setLifetimeRating(0)
                ->setCityRank(0);
        } else {
            $competitor
                ->setLifetimeReviews($competitorData->getReviews())
                ->setLifetimeRating($competitorData->getRating())
                ->setCityRank($competitorData->getCityRank());
        }

        return true;
    }

    ########################### END COMPETITOR SECTION #############################
    ############################ BEGIN TOTALS SECTION ##############################

    /**
     * Calculate totals to date
     *
     * @param  Reputation $reputation
     *
     * @return bool
     */
    private function calculateLifetimeTotals(Reputation $reputation)
    {
        /** @var ReputationEmailRepository $reputationEmailRepository */
        $reputationEmailRepository = $this->entityManager->getRepository('ApiBundle:ReputationEmail');

        /** @var ReputationCustomerRepository $reputationCustomerRepository */
        $reputationCustomerRepository = $this->entityManager->getRepository('ApiBundle:ReputationCustomer');

        $emailTotals = $reputationEmailRepository->getLifetimeTotals($reputation);

        if ($emailTotals) {
            $reputation
                ->setLifetimeSent($emailTotals['sent'])
                ->setLifetimeOpened($emailTotals['opened'])
                ->setLifetimeYes($emailTotals['yes'])
                ->setLifetimeNo($emailTotals['no'])
                ->setLifetimeRedirects($emailTotals['redirects']);
        }

        $customerTotals = $reputationCustomerRepository->getLifetimeTotals($reputation);

        if ($customerTotals) {
            $reputation->setTotalCustomers($customerTotals['customers']);
        }

        return true;
    }

    ############################# END TOTALS SECTION ###############################

    /**
     * Get data from Engage
     *
     * @param  string $method
     * @param  string $axAccountNumber
     * @param  DateTime $fromDate
     * @param  DateTime $toDate
     *
     * @return array|null
     */
    private function getEngageData($method, $axAccountNumber, DateTime $fromDate, DateTime $toDate = null)
    {
        // If we don't have $toDate, we'll set it to the last day of the month.
        if (!$toDate) {
            $toDate = clone $fromDate;
            $toDate->modify('last day of this month');
        }

        $data = array(
            'method' => 'reportingdashboard'.$method,
            'auth' => array('token' => $this->engageToken),
            'unique_id' => $axAccountNumber,
            'from_date' => $fromDate->format('Y-m-01'),
            'to_date' => $toDate->format('Y-m-d'),
        );

        $url = $this->baseUrl.json_encode($data);

        $this->progress->clear();
        $this->output->writeln("\n<info>".$data['method']."</info>");
        $this->progress->display();

        $curlHandle = curl_init();
        curl_setopt_array(
            $curlHandle,
            array(
                CURLOPT_URL => $url,
                CURLOPT_HEADER => false,
                CURLOPT_HTTPHEADER => array("Content-Type: application/json;"),
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_RETURNTRANSFER => true,
            )
        );

        // Limit ourselves to one call in two seconds.
        if (microtime(true) - $this->engageApiTimestamp < 2) {
            sleep(2);
        }

        $result = curl_exec($curlHandle);
        $errno = curl_errno($curlHandle);
        curl_close($curlHandle);

        $this->engageApiTimestamp = microtime(true);

        if ($errno) {
            $this->progress->clear();
            $this->output->writeln("\n<error>An error occurred with the request.</error>");
            $this->progress->display();

            return null;
        }

        return json_decode($result, true);
    }

    /**
     * Get reputation site if it exists. Otherwise, create it.
     *
     * @param  string $site The name of the review site.
     *
     * @return null|ReputationSite
     */
    private function getReputationSite($site)
    {
        $reputationSite = $this->entityManager->getRepository('ApiBundle:ReputationSite')
            ->findOneBy(['name' => $site]);

        // If we can't find an existing record, we'll create a new one.
        if (!$reputationSite) {
            $reputationSite = new ReputationSite();
            $reputationSite->setName($site);

            $this->entityManager->persist($reputationSite);
        }

        return $reputationSite;
    }

    /**
     * Truncate a given string
     *
     * @param  string $text The text to truncate
     * @param  int $length The length to truncate to
     *
     * @return string
     */
    private function truncate($text, $length = 25)
    {
        // If the passed in string is already shorter than $length,
        // We'll just return the string as is.
        if (strlen($text) < $length) {
            return $text;
        }

        // Otherwise, add a blank space to the end.
        $text = $text.' ';

        // get the first $length characters.
        $text = substr($text, 0, $length);

        // Trim the string to the last blank space.
        $text = substr($text, 0, strrpos($text, ' '));

        // Trim any punctuation.
        $text = rtrim($text, '.,!?:; ');

        // Add an ellipses to the end of the string.
        $text = $text.'...';

        return $text;
    }
}
