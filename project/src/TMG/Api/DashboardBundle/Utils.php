<?php

namespace TMG\Api\DashboardBundle;

use DateTime;
use DateInterval;
use Doctrine\ORM\EntityManager;

use FOS\RestBundle\Request\ParamFetcher;

use TMG\Api\ApiBundle\Entity\Books;
use TMG\Api\ApiBundle\Entity\SocialData;
use TMG\Api\ApiBundle\Entity\ReputationSiteData;
use TMG\Api\ApiBundle\Entity\ReputationSite;
use TMG\Api\ApiBundle\Entity\ReputationData;
use TMG\Api\ApiBundle\Entity\Reputation;
use TMG\Api\ApiBundle\Entity\ReputationReview;
use TMG\Api\ApiBundle\Entity\ReputationCompetitor;
use TMG\Api\ApiBundle\Entity\ReputationCompetitorData;
use TMG\Api\ApiBundle\Entity\ReputationEmail;
use TMG\Api\ApiBundle\Entity\ReputationCustomer;
use TMG\Api\ApiBundle\Entity\ReputationSurvey;
use TMG\Api\ApiBundle\Util\PagingInfo;

/**
 * Utils class.
 *
 */
class Utils
{
    protected $em;
    protected $baseUrl;

    // Entity

    /**
    * @var booksRepo
    */
    private $booksRepo;

    /**
    * @var socialDataRepo
    */
    private $socialDataRepo;

    /**
    * @var reputationSiteDataRepo
    */
    private $reputationSiteDataRepo;

    /**
    * @var reputationDataRepo
    */
    private $reputationDataRepo;

    /**
    * @var reputationRepo
    */
    private $reputationRepo;

    /**
    * @var reputationReviewRepo
    */
    private $reputationReviewRepo;

    /**
    * @var reputationSiteRepo
    */
    private $reputationSiteRepo;

    /**
    * @var reputationCompetitorRepo
    */
    private $reputationCompetitorRepo;

    /**
    * @var reputationCompetitorDataRepo
    */
    private $reputationCompetitorDataRepo;

    /**
    * @var reputationEmailRepo
    */
    private $reputationEmailRepo;

    /**
    * @var reputationCustomerRepo
    */
    private $reputationCustomerRepo;

    /**
    * @var reputationSurveyRepo
    */
    private $reputationSurveyRepo;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->baseUrl = 'http://tmg.engage121.com/service/tmg_index.php?format=json&data=';
        $this->booksRepo = $this->em->getRepository('ApiBundle:Books');
        $this->socialDataRepo = $this->em->getRepository('ApiBundle:SocialData');
        $this->reputationSiteDataRepo = $this->em->getRepository('ApiBundle:ReputationSiteData');
        $this->reputationDataRepo = $this->em->getRepository('ApiBundle:ReputationData');
        $this->reputationRepo = $this->em->getRepository('ApiBundle:Reputation');
        $this->reputationReviewRepo = $this->em->getRepository('ApiBundle:ReputationReview');
        $this->reputationSiteRepo = $this->em->getRepository('ApiBundle:ReputationSite');
        $this->reputationCompetitorRepo = $this->em->getRepository('ApiBundle:ReputationCompetitor');
        $this->reputationCompetitorDataRepo = $this->em->getRepository('ApiBundle:ReputationCompetitorData');
        $this->reputationEmailRepo = $this->em->getRepository('ApiBundle:ReputationEmail');
        $this->reputationCustomerRepo = $this->em->getRepository('ApiBundle:ReputationCustomer');
        $this->reputationSurveyRepo = $this->em->getRepository('ApiBundle:ReputationSurvey');
    }

    public function getBookList()
    {
        return $this->booksRepo->getBookList();
    }

    /**
     * Get all issue # from $start to $end
     *
     * @param String $start
     * @param String $end
     * @return Array
     */
    public function getIssues($start, $end)
    {
        $issues[] = $start;
        while (end($issues) < $end) {
            $date = substr(end($issues), 0, 2) . '-' . substr(end($issues), 2, 2) . '-01';
            $issues[] = date('ym', strtotime($date . ' + 1 month'));
        }

        return $issues;
    }

    // Handle Range
    public function handleRange($range)
    {
        $rangeResult = [];
        $start = new \DateTime('now');
        switch ($range) {
            case 30:
                $start->sub(new \DateInterval('P1M'));
                break;
            case 60:
                $start->sub(new \DateInterval('P2M'));
                break;
            case 90:
                $start->sub(new \DateInterval('P3M'));
                break;
            case 180:
                $start->sub(new \DateInterval('P6M'));
                break;
            case 365:
                $start->sub(new \DateInterval('P1Y'));
                break;
            case 'all':
                $start = null;
                break;
            default:
                $range = 365;
                $start->sub(new \DateInterval('P1Y'));
                break;
        }
        $rangeResult['range'] = $range;
        $rangeResult['start'] = $start;
        return $rangeResult;
    }


    public function handleSocial($id, $ax, $ts, $key)
    {
        $results = null;
        $result = $this->getSocialData($ax, $ts, $key);

        if ($result) {
            $results = $this->parseRepSocialData($result, true);
        } else {
            $socialTypes = $this->socialDataRepo->getSocialTypesByProperty($id);
            if ($socialTypes) {
                $results = $this->parseRepSocialData($socialTypes);
            }
        }
        return $results;
    }

    public function handleReporting($id, $end, $start = null)
    {
        $results = null;
        $data['sites'] = $this->reputationSiteDataRepo->getSiteDataByProperty($id, $start);
        //if ($start) {
        //    $data['totals'] = $this->reputationDataRepo->getTotalsByDate($id, $start);
        //} else {
            $data['totals'] = $this->reputationRepo->getTotalsByProperty($id);
        //}
        $tripAdvisor = $this->reputationSiteRepo->findOneByName('TripAdvisor');
        $data['tripAdvisorBreakdown'] = $this->reputationReviewRepo->getSiteBreakdownByProperty($id, $tripAdvisor);

        $results = $this->parseRepReportingData($data);

        return $results;
    }


    public function handleReviews($id, $end, $start = null)
    {
        $results = null;
        $tId = $this->reputationSiteRepo->findOneBy(array(
            'name'=> 'TripAdvisor'
        ));
        $tId = $tId->getId();
        $reviews = $this->reputationReviewRepo->getReviewsByProperty($id, $start);
        $sites = $this->reputationReviewRepo->getSitesByProperty($id, $start);
        $tripAdvisor = $this->reputationSiteDataRepo->getTripAdvisor($id, $tId);
        $results['sites'] = $sites;
        $results['reviews'] = $reviews;
        $results['trip_advisor'] = $tripAdvisor;
        return $results;
    }

    public function handleCompetitors($id, $end, $start = null)
    {
        $results = null;

        //if ($start) {
        //    $data['totals'] = $this->reputationCompetitorDataRepo->getTotalsByDate($id);
        //} else {
            $data['totals'] = $this->reputationCompetitorRepo->getTotalsByProperty($id);
        //}
        $data['competitors'] = $this->reputationCompetitorRepo->findByProperty($id);

        $results = $this->parseRepCompetitorData($data, $id, $start);

        return $results;
    }

    public function handleInfluence($id, $end, $start = null)
    {
        $results = null;
        if ($start) {
            $emailStats = $this->reputationEmailRepo->getStatsByDate($id, $start);
            if ($emailStats) {
                $emailStats = $emailStats[0];
            }
            //$customerStats = $this->reputationCustomerRepo->getStatsByDate($id, $start);
            $customerStats = $this->reputationRepo->getInfluenceTotalsByProperty($id);
            if ($customerStats) {
                $customerStats = $customerStats[0];
            }
            $data['totals'] = array_merge($emailStats, $customerStats);
        } else {
            $data['totals'] = null;
            $stats = $this->reputationRepo->getInfluenceTotalsByProperty($id);
            if ($stats) {
                $data['totals'] = $stats[0];
            }
        }

        $results = $this->parseRepInfluenceData($data, $start);
        return $results;
    }

    public function handleEngagement($id, $end, $start = null)
    {
        $results = null;
        if ($start) {
            $data = $this->reputationEmailRepo->getAllByPropertyAndDate($id, $start);
        } else {
            $data = $this->reputationEmailRepo->getAllByProperty($id);
        }
        $customers = $this->reputationCustomerRepo->getAllByProperty($id);

        $results = $this->parseRepEngagementData($data, $customers, $id);
        return $results;
    }

    private function getSocialData($aNum, $ts, $key)
    {
        $url = 'http://tmgsocial.engage121.com/tmg_socialscape.php?id=';
        $url .= $aNum;
        $url .= '&ts=';
        $url .= $ts;
        $url .= '&key=';
        $url .= $key;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json;"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if ($errno) {
            ladybug_dump($error);
            return null;
        }

        return json_decode($result);
    }

    private function parseRepSocialData($data, $engage = false)
    {
        $connected = [];

        if ($engage) {
            foreach ($data->metadata->lps as $d) {
                $acc = array(
                    'slug' => $d->system_name,
                    'name' => $d->friendly_name,
                );
                array_push($connected, $acc);
            }
        } else {
            foreach ($data as $d) {
                $acc = array(
                    'slug' => $d['type'],
                    'name' => $d['name'],
                );
                array_push($connected, $acc);
            }
        }

        if ($connected) {
            return $connected;
        } else {
            return null;
        }
    }

    private function parseRepReportingData($data)
    {
        $reporting['current_sites'] = [];
        $reporting['current_sites_total'] = [];
        $reporting['external_totals'] = [];

        $siteList = null;
        $totals = null;
        // Current Sites
        if ($data) {
            $reviews = 0;
            $average = 0;
            $sites = $data['sites'];
            foreach ($sites as $d) {
                $siteList[$d['name']] = array(
                    'reviews' => $d['reviews'],
                    'average' => $d['avg_rating'],
                );
                $rAvg = floatval($d['avg_rating']);
                $reviews = ($reviews + (int) $d['reviews']);
                $average = ($average + $rAvg);
            }
            $tAverage = 0;
            if (count($sites) > 0 && $average != 0) {
                $tAverage = ($average / count($sites));
            }
            $total = array(
                'reviews' => $reviews,
                'average' => $tAverage,
            );
        }
        $reporting['current_sites'] = $siteList;
        $reporting['current_sites_total'] = $total;

        // External Reviews
        $exAverage = 0;
        $exTotals = 0;
        $positive = 0;
        $tripRating = 0;
        $tripPositive = 0;
        $tripNegative = 0;
        $tripRank = 'N/A';
        $totals = $data['totals'];
        $tripAdvisorBreakdown = $data['tripAdvisorBreakdown'];
        if ($totals) {
            $exAverage = $totals[0]['avg_rating'];
            $exTotals = $totals[0]['reviews'];
            $positive = $totals[0]['positive'];
            $tripRating = $totals[0]['trip_rating'];
            $tripRank = $totals[0]['trip_rank'];
        }
        if ($tripAdvisorBreakdown) {
            $tripPositive = $tripAdvisorBreakdown['positive'];
            $tripNegative = $tripAdvisorBreakdown['negative'];
        }
        $reporting['external_totals'] = array(
            'average' => $exAverage,
            'total' => $exTotals,
            'positive' => $positive,
            'trip_rating' => $tripRating,
            'trip_rank' => $tripRank,
            'trip_positive' => $tripPositive,
            'trip_negative' => $tripNegative,
        );
        return $reporting;
    }

    private function parseRepReviewData($data, $engage = false)
    {
        $results = [];
        $reviews = [];
        $sites = [];
        if ($engage) {
            if (isset($data->reviews)) {
                foreach ($data->reviews as $r) {
                    $result = [];
                    $result['resolved'] = null;
                    $result['engageId'] = $r->guid;
                    $result['siteName'] = $r->site_name;
                    $result['postDate'] = new \DateTime($r->post_date);
                    $result['username'] = $r->user_name;
                    $result['contentShort'] = $r->content_short;
                    $result['contentUrl'] = $r->content_url;
                    $result['tone'] = $r->tone;
                    if ((int) $r->tone >= 3) {
                        $result['sentiment'] = 1;
                    } else {
                        $result['sentiment'] = 2;
                    }
                    array_push($reviews, $result);

                    if (!in_array($r->site_name, $sites)) {
                        array_push($sites, $r->site_name);
                    }
                }
            } else {
                $results = null;
            }
        }

        $results['sites'] = $sites;
        $results['reviews'] = $reviews;
        return $results;
    }

    private function parseRepCompetitorData($data, $id, $start = null)
    {
        $competitors['market'] = [];
        $competitors['market_total'] = [];
        $competitors['property_total'] = [];
        $competitors['competitors_data'] = [];

        if ($data) {
            $totals = [];
            // Market Breakdown
            $market = $data['totals'];
            $mTotal = 0;
            if ($market) {
                foreach ($market as $m) {
                    if (array_key_exists($m['name'], $totals)) {
                        $totals[$m['name']] += $m['reviews'];
                        continue;
                    }

                    $totals[$m['name']] = $m['reviews'];
                }

                foreach ($market as $m) {
                    $mTotal = ($mTotal + $m['reviews']);
                }
            }
            // Competitors
            $cList = [];
            $propertyTotal = [];
            foreach ($data['competitors'] as $c) {
                $address = $c->getAddress();
                $lat = null;
                $lon = null;
                if ($address) {
                    $lat = $address->getLatitude();
                    $lon = $address->getLongitude();
                }

                $lifetime = $this->reputationCompetitorDataRepo->getAllSiteDataByCompetitor($c->getId());

                if (!$lifetime) {
                    $previousMonth = new DateTime();
                    $dateInterval = new DateInterval('P1M');

                    // 1505 is the first month that any reputation data from engage exists.
                    while (empty($lifetime) && $previousMonth->format('ym') > '1505') {
                        $previousMonth = $previousMonth->sub($dateInterval);

                        $lifetime = $this->reputationCompetitorDataRepo->getAllSiteDataByCompetitor(
                            $c->getId(),
                            $previousMonth->format('ym')
                        );
                    }
                }

                $sites = [];
                $siteIds = [];
                if ($lifetime) {
                    foreach ($lifetime as $lt) {
                        $siteIds[] = $lt['id'];
                        $sites[$lt['name']] = array(
                            'url' => $lt['url'],
                            'current_rating' => null,
                            'current_reviews' => null,
                            'lifetime_reviews' => $lt['reviews'],
                            'lifetime_rating' => $lt['rating'],
                            'delta' => null,
                            'slug' => $this->slugify($lt['name']),
                            'month_total' => $lt['monthTotal'],
                            'city_rank' => $lt['cityRank']
                        );
                    }
                }

                // Only include totals from sites that competitors also have
                $propertyTotal = $this->reputationSiteDataRepo->getCompetitorTotalByProperty($id, $siteIds);

                $cList[] = array(
                    'name' => $c->getName(),
                    'city_rank' => $c->getCityRank(),
                    'lat' => $lat,
                    'lon' => $lon,
                    'life_rating' => $c->getLifetimeRating(),
                    'life_reviews' => $c->getLifetimeReviews(),
                    'sites' => $sites,
                );
            }

            $competitors['market'] = $totals;
            $competitors['market_total'] = $mTotal;
            $competitors['property_total'] = $propertyTotal;
            $competitors['competitors_data'] = $cList;
        }

        return $competitors;
    }

    private function parseRepInfluenceData($data)
    {
        $results = [];
        $stats = [];

        if ($data['totals']) {
            $t = $data['totals'];
            $stats['yes_clicks'] = 0;
            $stats['no_clicks'] = 0;
            $stats['customers'] = 0;
            $stats['sent'] = 0;
            $stats['last_upload'] = null;
            if (array_key_exists('yes_clicks', $t) && $t['yes_clicks']) {
                $stats['yes_clicks'] = $t['yes_clicks'];
            }
            if (array_key_exists('no_clicks', $t) && $t['no_clicks']) {
                $stats['no_clicks'] = $t['no_clicks'];
            }
            if (array_key_exists('customers', $t) && $t['customers']) {
                $stats['customers'] = $t['customers'];
            }
            if (array_key_exists('last_upload', $t) && $t['last_upload']) {
                $stats['last_upload'] = $t['last_upload']->format('m/d/Y');
            }
            if (array_key_exists('sent', $t) && $t['sent']) {
                $stats['sent'] = $t['sent'];
            }
        }

        $results['stats'] = $stats;
        return $results;
    }

    private function parseRepEngagementData($data, $customers, $id)
    {
        $results = [];
        if ($data) {
            $emails = [];
            foreach ($data as $e) {
                $yrmo = $e['yrmo'];
                $year = substr($yrmo, 0, -2);
                $month = substr($yrmo, -2);
                $year = '20'.$year;
                $formatDate = $year.'-'.$month.'-01';
                $newDate = new \DateTime($formatDate);
                $now = new \DateTime('now');
                $now = $now->format('ym');
                if ((int) $now == $yrmo) {
                    $datePretty = 'Current Month';
                } else {
                    $datePretty = $newDate->format('M Y');
                }

                $sent = 0;
                if ($e['sent']) {
                    $sent = $e['sent'];
                }
                $opened = 0;
                if ($e['opened']) {
                    $opened = $e['opened'];
                }
                $redirects = 0;
                if ($e['redirects']) {
                    $redirects = $e['redirects'];
                }

                $exTotal = 0;
                $exData = $this->reputationDataRepo->findTotalByPropertyAndYrmo($id, $yrmo);
                if ($exData) {
                    $exTotal = $exData['externalTotal'];
                }
                $ePerEx = 0;
                if ($exTotal != 0 && $sent != 0) {
                    $ePerEx = ($sent/$exTotal);
                }
                $emails[$yrmo] = array(
                    'date' => $datePretty,
                    'sent' => $sent,
                    'opened' => $opened,
                    'redirects' => $redirects,
                    'external' => $exTotal,
                    'email_external' => $ePerEx,
                );

                $completed = 0;
                $started = 0;
                if ($customers) {
                    foreach ($customers as $customer) {
                        if (strtolower($customer['status']) == 'completed' &&
                            $customer['uploadDate'] instanceof \DateTime &&
                            $customer['uploadDate']->format('ym') == $yrmo) {
                            $completed++;
                        }

                        if (strtolower($customer['status']) == 'start' &&
                            $customer['uploadDate'] instanceof \DateTime &&
                            $customer['uploadDate']->format('ym') == $yrmo) {
                            $started++;
                        }
                    }
                }
                $emails[$yrmo]['completed'] = $completed;
                $emails[$yrmo]['started'] = $started + $completed;
                $emails[$yrmo]['feedback'] = ($completed + $exTotal);
            }

            $results = $emails;
        }

        return $results;
    }

    public function getCustomerCount($id)
    {
        return $this->reputationCustomerRepo->getCountByProperty($id);

    }

    public function getAllCustomers($id)
    {
        $data = $this->reputationCustomerRepo->getAllByProperty($id);
        $results = $this->parseCustomers($data);

        return $results;
    }

    private function parseCustomers($data)
    {
        $customers = [];
        if ($data) {
            foreach ($data as $c) {
                $uploadDate = null;
                $check = null;
                if ($c['uploadDate']) {
                    $uploadDate = $c['uploadDate']->format('m/d/y H:i:s');
                }

                if ($c['checkoutDate']) {
                    $check = $c['checkoutDate']->format('m/d/y H:i:s');
                }

                $cust = array(
                    'first' => $c['firstName'],
                    'last' => $c['lastName'],
                    'email' => $c['email'],
                    'uploadDate' => $uploadDate,
                    'checkout' => $check,
                    'status' => $c['status'],
                );
                array_push($customers, $cust);
            }
        }
        return $customers;
    }

    /*
     * Slugify
     */
    private function slugify($string)
    {
        $slug = str_replace('.', '', $string);
        $slug = preg_replace('/[^a-z0-9\/]/i', '-', strtolower($slug));
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = str_replace('/', '_', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
}
