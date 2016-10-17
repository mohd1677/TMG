<?php

namespace TMG\Console\CommandBundle\Parser;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use TMG\Api\ApiBundle\Entity\Analytic;
use TMG\Api\ApiBundle\Entity\DeviceType;
use TMG\Console\CommandBundle\BaseParser;

class OmnitureParser extends BaseParser
{

    //Web Suite IDs
    const WEB_SUITE_ID = 'de-hc-responsive';
    const IPAD_SUITE_ID = 'deroomsaveripad';
    const IPHONE_SUITE_ID = 'deroomsaveriphone';

    //Property and Evar values to check for on HotelCoupons.com
    const PROP_ONLINE_RATE_CLICK = 'prop1';
    const PROP_COUPON_VIEW = 'prop2';
    const PROP_DETAIL_VIEW = 'prop3';
    const PROP_FEATURE_AD_CLICK = 'prop4';
    const EVAR_HOTEL_VIEWED = 'evar3';
    const EVAR_COUPON_VIEWED = 'evar4';
    const EVAR_ONLINE_RATE_VIEWED = 'evar5';

    //endpoint to hit on the api to get data
    const ENDPOINT = "https://api.omniture.com/admin/1.3/rest/";

    /** @var \DateTime */
    protected $reportDate;

    /** @var array */
    protected $queuedReports = [];

    /** @var int */
    protected $newRecords = 0;

    /**
     * Set the report date to run from
     * @param $date
     */
    public function setReportDate($date)
    {
        $interval = new \DateInterval('P1D');

        if ($date) {
            $this->reportDate = new \DateTime($date);
        } else {
            // If no records are returned, the repo will instead, return a hard-coded date of Jan 1, 2013.
            $this->reportDate = $this->entityManager->getRepository('ApiBundle:Analytic')->getMostRecentDate();

            if ($this->reportDate > new \DateTime('January 1, 2013')) {
                $this->reportDate->add($interval);
            }
        }
    }

    /**
     * Queues up reports in the omniture api to be retrieved later
     * by the reportId
     *
     * @param string $date
     *
     * @return string
     */
    public function queueAndProcessReports($date)
    {
        $suites = [
            'desktop' => self::WEB_SUITE_ID,
            'ipad' => self::IPAD_SUITE_ID,
            'iphone' => self::IPHONE_SUITE_ID,
        ];

        $this->setReportDate($date);

        $interval = new \DateInterval('P1D');

        do {
            foreach ($suites as $platform => $suiteId) {
                if (in_array($platform, ['iphone', 'ipad'])) {
                    $omnitureProps = [
                        'onlinerate' => self::EVAR_ONLINE_RATE_VIEWED,
                        'coupon' => self::EVAR_COUPON_VIEWED,
                        'detailview' => self::EVAR_HOTEL_VIEWED,
                    ];
                } else {
                    $omnitureProps = [
                        'onlinerate' => self::PROP_ONLINE_RATE_CLICK,
                        'coupon' => self::PROP_COUPON_VIEW,
                        'detailview' => self::PROP_DETAIL_VIEW,
                        'featuredad' => self::PROP_FEATURE_AD_CLICK,
                    ];
                }

                foreach ($omnitureProps as $type => $prop) {
                    $this->addToQueue($platform, $type, $prop, $suiteId);
                }
            }
            $this->processReports();
            $this->reportDate->add($interval);
        } while ($this->reportDate < (new \DateTime('00:00:00')));

        return $this->newRecords.' records imported.';
    }

    /**
     * Sets up the request body to be made and passes it to getData
     *
     * @param string $platform
     * @param string $type
     * @param string $prop
     * @param string $suiteId
     */
    private function addToQueue($platform, $type, $prop, $suiteId)
    {
        $strDate = $this->reportDate->format('Y-m-d');

        $method = 'Report.QueueRanked';
        $metricType = 'pageviews';

        if (in_array($platform, ['iphone', 'ipad'])) {
            $metricType = 'instances';
        } elseif (in_array($type, ['featuredad', 'onlinerate'])) {
            $metricType= 'instances';
        }

        $data = [
            'reportDescription' => [
                'reportSuiteID' => $suiteId,
                'dateFrom' => $strDate,
                'dateTo' => $strDate,
                'metrics' => [
                    ['id' => $metricType]
                ],
                'elements' => [
                    [
                    'id' => $prop,
                    'top' => "50000"
                    ]
                ]
            ]
        ];

        $data = json_encode($data);

        /** @var Response $response */
        $response = $this->getData($method, $data);

        if ($response->getStatusCode() == 200) {
            $result = json_decode($response->getBody()->getContents());

            if ($result->status == 'queued') {
                $reportId = $result->reportID;
                $report = [
                    'id' => $reportId,
                    'reportDate' => clone $this->reportDate,
                    'platform' => $platform,
                    'type' => $type,
                ];
                array_push($this->queuedReports, $report);
                $this->output->writeln("Queued report $reportId");
            } else {
                $this->output->writeln("Unable to queue report");
            }
        } else {
            $this->output->writeln("Non 200 response code");
        }
    }

    /**
     * @param string $method
     * @param array $data
     * @return Response
     */
    private function getData($method, $data)
    {
        $username = $this->container->getParameter('omniture_username');
        $secret = $this->container->getParameter('omniture_secret');
        $nonce = md5(uniqid(php_uname('n'), true));
        $nonceTs = date('c');
        $digest = base64_encode(sha1($nonce . $nonceTs . $secret));

        /** @var Client $client */
        $client = new Client(['exceptions' => false]);

        /** @var Response $response */
        $response = $client->post(
            self::ENDPOINT,
            [
                'body' => $data,
                'query' => ['method' => $method],
                'headers' => [
                    "X-WSSE" => "UsernameToken Username=\"$username\",
                        PasswordDigest=\"$digest\",
                        Nonce=\"$nonce\",
                        Created=\"$nonceTs\""
                ]
            ]
        );

        return $response;
    }

    public function processReports()
    {
        $method = 'Report.GetReport';

        foreach ($this->queuedReports as $report) {
            if ($this->getReportStatus($report['id'])) {
                $this->output->writeln("Processing {$report['id']}");
                $data = ["reportID" => $report['id']];
                $data = json_encode($data);
                $this->reportDate = $report['reportDate'];
                $platform = $report['platform'];
                $type = $report['type'];

                $response = $this->getData($method, $data);

                if ($response->getStatusCode() == 200) {
                    $result = json_decode($response->getBody()->getContents());
                    for ($i = 0; $i < count($result->report->data); $i++) {
                        if (isset($result->report->data[$i])) {
                            $output = $result->report->data[$i];
                            $totalCount = $output->counts[0];
                            $propertyDetail = explode("|", $output->name);
                            if (isset($propertyDetail[1])) {
                                $propertyId = trim($propertyDetail[1]);
                                $this->processBreakdownData($propertyId, $totalCount, $type, $platform);
                            } else {
                                if ((strpos($propertyDetail[0], "$") === 0)
                                    || (strpos($propertyDetail[0], "Call for Rate") === 0)
                                    || (strpos($propertyDetail[0], "From $") === 0)
                                    || (strpos($propertyDetail[0], "Online Rate") === 0)
                                    || (strpos($propertyDetail[0], "% Off") === 2)) {
                                    if (strpos($propertyDetail[0], "-")) {
                                        $propertyDetailAgain = explode("-", $propertyDetail[0]);
                                        $rateValue = trim($propertyDetailAgain[0]);
                                        $rateValue = $rateValue . " -";
                                        $propertyName = str_replace($rateValue, "", $propertyDetail[0]);
                                        $searchPropertyId = $this->propertyByName(trim($propertyName));
                                        if ($searchPropertyId) {
                                            $this->processBreakdownData(
                                                $searchPropertyId,
                                                $totalCount,
                                                $type,
                                                $platform
                                            );
                                        }
                                    }
                                } else {
                                    $searchPropertyId = $this->propertyByName($propertyDetail[0]);
                                    if ($searchPropertyId) {
                                        $this->processBreakdownData(
                                            $searchPropertyId,
                                            $totalCount,
                                            $type,
                                            $platform
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->queuedReports = [];
    }

    private function processBreakdownData($propertyId, $totalCount, $type, $platform)
    {
        if (!empty($propertyId)) {
            $property = $this->entityManager
                ->getRepository('ApiBundle:Property')
                ->findOneBy(['hash' => $propertyId]);

            $device = $this->entityManager
                ->getRepository('ApiBundle:DeviceType')
                ->findOneBy(['name' => $platform]);

            if ($property) {
                /** @var Analytic $record */
                $record = $this->entityManager
                    ->getRepository('ApiBundle:Analytic')
                    ->findOneBy(
                        [
                            'property' => $property,
                            'reportDate' => $this->reportDate,
                            'device' => $device,
                        ]
                    );

                if ($record) {
                    if ($type == 'onlinerate') {
                        $record->setOnlineRateClicks($totalCount);
                    } elseif ($type == 'coupon') {
                        $record->setCouponViews($totalCount);
                    } elseif ($type == 'featuredad') {
                        $record->setFeaturedAdClicks($totalCount);
                    } elseif ($type == 'detailview') {
                        $record->setDetailViews($totalCount);
                    }

                    $this->entityManager->persist($record);
                    $this->entityManager->flush();
                } else {
                    $onlinerate = $coupon = $featuredad = $detailview = 0;

                    $record = new Analytic();
                    $record->setProperty($property);
                    $record->setReportDate($this->reportDate);

                    if ($type == 'onlinerate') {
                        $onlinerate = $totalCount;
                    } elseif ($type == 'coupon') {
                        $coupon = $totalCount;
                    } elseif ($type == 'featuredad') {
                        $featuredad = $totalCount;
                    } elseif ($type == 'detailview') {
                        $detailview = $totalCount;
                    }

                    if (!$device) {
                        $device = new DeviceType();
                        $device->setName($platform);
                    }

                    $record->setOnlineRateClicks($onlinerate);
                    $record->setCouponViews($coupon);
                    $record->setFeaturedAdClicks($featuredad);
                    $record->setDetailViews($detailview);
                    $record->setDevice($device);

                    $this->entityManager->persist($record);
                    $this->entityManager->flush();
                    $this->entityManager->clear();

                    $this->newRecords++;
                }
            }
        }
    }

    private function propertyByName($propertyName)
    {
        $lookup = $this->entityManager
            ->getRepository('ApiBundle:Property')
            ->findBy(['name' => $propertyName]);

        if (count($lookup) == 1) {
            return $lookup[0]->getHash();
        } else {
            return false;
        }
    }

    /**
     * Check if reportId is ready for processing
     *
     * @param int $reportId
     * @return bool
     */
    public function getReportStatus($reportId)
    {
        $done = false;
        $error = false;
        $i = 0;

        while (!$done && !$error) {
            if (!$i === 0) {
                echo ".";
                sleep(15);
            }

            $method = 'Report.GetStatus';
            $data = [ 'reportID' => $reportId ];
            $data = json_encode($data);

            /** @var Response $response */
            $response = $this->getData($method, $data);

            if ($response->getStatusCode() == 200) {
                $result = json_decode($response->getBody()->getContents());
                if ($result->status == 'done') {
                    $this->output->writeln("Report $reportId is ready");
                    $done = true;
                } elseif ($result->status == 'failed') {
                    $this->output->writeln("Report Generation Failed for $reportId");
                    $error = false;
                }
            } else {
                $this->output->writeln("Non 200 response code");
                return false;
            }

            $i++;
        }

        return true;
    }
}
