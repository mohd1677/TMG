<?php

namespace TMG\Api\LegacyBundle\Formatting;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

use TMG\Api\LegacyBundle\Entity\Address;
use TMG\Api\LegacyBundle\Entity\CmsBlock;
use TMG\Api\LegacyBundle\Entity\CombinedListing;
use TMG\Api\LegacyBundle\Entity\Repository\CityCenterRepository;
use TMG\Api\LegacyBundle\Entity\Repository\CombinedListingRepository;
use TMG\Api\LegacyBundle\Entity\Repository\ZipCodeRepository;

class LocationFormatter
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var EntityRepository
     */
    protected $blockRepo;

    /**
     * @var CombinedListingRepository
     */
    protected $combinedRepo;

    /**
     * @var CityCenterRepository
     */
    protected $cityRepo;

    /**
     * @var ZipCodeRepository
     */
    protected $zipRepo;


    private $usableResults;

    /**
     * @param EntityManager $em
     * @param Container $container
     */
    public function __construct(ManagerRegistry $managerRegistry, Container $container)
    {
        $this->container = $container;
        $this->em = $managerRegistry->getManager("legacy");
        $this->blockRepo = $this->em->getRepository('TMGApiLegacyBundle:CmsBlock');
        $this->combinedRepo = $this->em->getRepository('TMGApiLegacyBundle:CombinedListing');
        $this->cityRepo = $this->em->getRepository('TMGApiLegacyBundle:CityCenter');
        $this->zipRepo = $this->em->getRepository('TMGApiLegacyBundle:ZipCode');
    }


    /**
     * @return array
     */
    public function getCmsContent()
    {
        /** @var CmsBlock $cmsBlock */
        $cmsBlock = $this->blockRepo->findBy(["slug" => "search-results-page"]);
        $cmsBlock = $cmsBlock[0];

        $cmsContent = [];
        $widgets = $cmsBlock->getWidgets();

        foreach ($widgets as $widget) {
            $cmsContent[$widget->getSlug()] = $widget;
        }

        return $cmsContent;
    }

    /**
     * @param $slug string
     * @return array
     */
    public function getCmsBlocks($slug)
    {
        $cmsBlock = $this->blockRepo->findBy(["slug" => $slug]);

        return $cmsBlock;
    }


    /**
     * @param $name
     * @return mixed|string
     */
    public function slugify($name)
    {
        $slug = preg_replace('/[^a-z0-9\/]/i', '-', strtolower($name));
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        return $slug;
    }

    /**
     * @param array $featuredLocations
     * @param $state
     * @return array
     */
    public function findAdditionalFeaturedByState(array $featuredLocations, $state)
    {
        $lat = null;
        $lon = null;
        /** @var CombinedListing $f */
        foreach ($featuredLocations as $f) {
            /** @var Address $address */
            $address = $f->getAddress();

            if ($address->getLatitude() && $address->getLongitude()) {
                $lat = $address->getLatitude();
                $lon = $address->getLongitude();
            }
        }
        if ($lat && $lon) {
            $max = (6 - count($featuredLocations));
            $additionalFeatured = $this->combinedRepo->getFeaturedByCoordsExcludeState($lat, $lon, $state, $max);
        } else {
            $max = (6 - count($featuredLocations));
            $additionalFeatured = $this->combinedRepo->getRandomFeaturedExcludeState($state, $max);
        }

        return array_merge($featuredLocations, $additionalFeatured);

    }

    /**
     * @param $param
     * @return array
     */
    public function filterSearchParams($param)
    {
        $this->usableResults = array(
            'state'=>'',
            'stateCode' => '',
            'city' => '',
            'lat' => '',
            'lon' => '',
            'zip' => '',
        );
        // TODO: Use a single reg-ex for this
        $formatParam = trim($param);
        $formatParam = str_replace(" ", "-", $formatParam);
        $formatParam = str_replace(",", "-", $formatParam);
        $formatParam = str_replace("/", "-", $formatParam);
        $formatParam = str_replace("%", "-", $formatParam);
        $formatParam = str_replace("*", "-", $formatParam);
        $formatParam = str_replace(".", "-", $formatParam);
        $formatParam = str_replace("_", "-", $formatParam);
        $formatParam = str_replace("--", "-", $formatParam);
        $formatSearch = explode('-', strtolower($formatParam));

        switch (count($formatSearch)) {
            // Account for single word search
            case 1:
                // First check for zip
                if (is_numeric($formatSearch[0])) {
                    $this->usableResults['zip'] = $formatSearch[0];
                } else {
                    // Check for single word state
                    $state = $this->findStateSingle($formatSearch);
                    if ($state) {
                        $this->usableResults['state'] = $state;
                    } else {
                        // Check for state abbreviation
                        $stateAbbr = $this->findStateSingleAbbr($formatSearch);
                        if ($stateAbbr) {
                            $this->usableResults['state'] = $stateAbbr['state'];
                            $this->usableResults['stateCode'] = $stateAbbr['stateCode'];
                        } else {
                            // If we havent found it by now its most likely a city
                            $this->usableResults['city'] = $formatSearch[0];
                        }
                    }
                }
                break;
            case 2:
                if ($formatParam == 'west-virginia') {
                    $this->usableResults['state'] = $formatParam;
                    $this->usableResults['stateCode'] = 'WV';
                } elseif (strtolower($formatSearch[0]) == 'washington') {
                    $stateList = $this->stateData();
                    $stateLast = $formatSearch[1];
                    if (strlen($stateLast) == 2) {
                        $sCode = strtoupper($stateLast);
                        $state = strtolower($stateList[$sCode]);
                        $this->usableResults['state'] = $state;
                        $this->usableResults['stateCode'] = $sCode;
                    } else {
                        $state = strtolower($stateLast);
                        $this->usableResults['state'] = $state;
                    }
                    $this->usableResults['city'] = 'washington';
                } else {
                    $stateSingle = $this->findStateSingle($formatSearch, 2);
                    if ($stateSingle) {
                        $this->usableResults['state'] = $formatSearch[$stateSingle['position']];
                        if ($stateSingle['position'] == 0) {
                            $this->usableResults['city'] = $formatSearch[1];
                        } else {
                            $this->usableResults['city'] = $formatSearch[0];
                        }
                    } else {
                        $stateAbbr = $this->findStateSingleAbbr($formatSearch);
                        if ($stateAbbr) {
                            if ($stateAbbr['position'] == 0) {
                                $this->usableResults['city'] = $formatSearch[1];
                            } else {
                                $this->usableResults['city'] = $formatSearch[0];
                            }
                            $this->usableResults['state'] = $stateAbbr['state'];
                            $this->usableResults['stateCode'] = $stateAbbr['stateCode'];
                        } else {
                            $doubleState = $this->findStateDouble($formatSearch, true);
                            if ($doubleState) {
                                $this->usableResults['state'] = $doubleState;
                            } else {
                                $this->usableResults['city'] = strtolower($formatParam);
                            }
                        }
                    }
                }
                break;
            case 3:
                $wv = $this->findWestVirginia($formatSearch);
                if ($wv) {
                    $this->usableResults['state'] = $wv['state'];
                    $this->usableResults['stateCode'] = 'WV';
                    $this->usableResults['city'] = $wv['city'];
                } elseif (strtolower($formatSearch[0]) == 'washington') {
                    $stateList = $this->stateData();
                    $stateLast = array_pop($formatSearch);
                    if (strlen($stateLast) == 2) {
                        $sCode = strtoupper($stateLast);
                        $state = strtolower($stateList[$sCode]);
                        $this->usableResults['state'] = $state;
                        $this->usableResults['stateCode'] = $sCode;
                    } else {
                        $state = strtolower($stateLast);
                        $this->usableResults['state'] = $state;
                    }
                    $city = implode('-', $formatSearch);
                    $this->usableResults['city'] = $city;
                } else {
                    if ($formatParam == 'district-of-columbia') {
                        $this->usableResults['state'] = $formatParam;
                        $this->usableResults['stateCode'] = 'DC';
                    } else {
                        $stateSingle = $this->findStateSingle($formatSearch, 3);
                        if ($stateSingle) {
                            $this->usableResults['state'] = $stateSingle['state'];
                            $this->usableResults['city'] = $formatSearch[0].' '.$formatSearch[1];
                        } else {
                            $stateAbbr = $this->findStateSingleAbbr($formatSearch);
                            if ($stateAbbr) {
                                $this->usableResults['city'] = $formatSearch[0].' '.$formatSearch[1];
                                $this->usableResults['state'] = $stateAbbr['state'];
                                $this->usableResults['stateCode'] = $stateAbbr['stateCode'];
                            } else {
                                $doubleState = $this->findStateDouble($formatSearch, true);
                                if ($doubleState) {
                                    $this->usableResults['state'] = $doubleState;
                                    $this->usableResults['city'] = $formatSearch[0];
                                } else {
                                    $this->usableResults['city'] = strtolower($formatParam);
                                }
                            }
                        }
                    }
                }
                break;
            default:
                $wv = $this->findWestVirginia($formatSearch);
                if ($wv) {
                    $this->usableResults['state'] = $wv['state'];
                    $this->usableResults['stateCode'] = 'WV';
                    $this->usableResults['city'] = $wv['city'];
                } elseif (strtolower($formatSearch[0]) == 'washington') {
                    $stateList = $this->stateData();
                    $stateLast = array_pop($formatSearch);
                    if (strlen($stateLast) == 2) {
                        $sCode = strtoupper($stateLast);
                        $state = strtolower($stateList[$sCode]);
                        $this->usableResults['state'] = $state;
                        $this->usableResults['stateCode'] = $sCode;
                    } else {
                        $state = strtolower($stateLast);
                        $this->usableResults['state'] = $state;
                    }
                    $city = implode('-', $formatSearch);
                    $this->usableResults['city'] = $city;
                } else {
                    $stateSingle = $this->findStateSingle($formatSearch, 3);
                    if ($stateSingle) {
                        $this->usableResults['state'] = array_pop($formatSearch);
                        $this->usableResults['city'] = implode(' ', $formatSearch);
                    } else {
                        $stateAbbr = $this->findStateSingleAbbr($formatSearch);
                        if ($stateAbbr) {
                            $this->usableResults['state'] = $stateAbbr['state'];
                            $this->usableResults['stateCode'] = array_pop($formatSearch);
                            $this->usableResults['city'] = implode(' ', $formatSearch);
                        } else {
                            $doubleState = $this->findStateDouble($formatSearch);
                            if ($doubleState) {
                                $this->usableResults['state'] = $doubleState['state'];
                                $this->usableResults['city'] = $doubleState['city'];
                            } else {
                                $this->usableResults['city'] = strtolower($formatParam);
                            }
                        }
                    }
                }
                break;
        }

        if ($this->usableResults['state'] && !$this->usableResults['stateCode']) {
            $this->usableResults['stateCode'] = array_search(
                ucwords($this->usableResults['state']),
                $this->stateData()
            );
        }

        if ($this->usableResults['city'] && $this->usableResults['state']) {
            $geocode = $this->cityRepo->findOneBy(array(
                'city' => ucwords($this->usableResults['city']),
                'state' => strtoupper($this->usableResults['stateCode'])
            ));
            if ($geocode) {
                if ($geocode->getLatitude() && $geocode->getLongitude()) {
                    $this->usableResults['lat'] = $geocode->getLatitude();
                    $this->usableResults['lon'] = $geocode->getLongitude();
                }
            }
        }
        if ($this->usableResults['zip']) {
            $geocode = $this->zipRepo->findOneBy(array(
                'zip' => $this->usableResults['zip'],
            ));
            if ($geocode) {
                if ($geocode->getLatitude() && $geocode->getLongitude()) {
                    $this->usableResults['lat'] = $geocode->getLatitude();
                    $this->usableResults['lon'] = $geocode->getLongitude();
                }
            }
        }

        return $this->usableResults;
    }

    /**
     * @param $formatSearch
     * @param null $count
     * @return null
     */
    private function findStateSingle($formatSearch, $count = null)
    {
        $states = $this->stateData();
        foreach ($states as $state) {
            for ($i=0; $i < count($formatSearch); $i++) {
                if (strtolower($state) == strtolower($formatSearch[$i])) {
                    if ($count) {
                        $stateResult['state'] = $formatSearch[$i];
                        $stateResult['position'] = $i;
                        return $stateResult;
                    } else {
                        return $formatSearch[$i];
                    }
                }
            }
        }
        return null;

    }

    /**
     * @param $formatSearch
     * @return null
     */
    private function findStateSingleAbbr($formatSearch)
    {
        $states = $this->stateData();
        foreach ($states as $abbr => $state) {
            for ($i=0; $i < count($formatSearch); $i++) {
                if (strtolower($abbr) == strtolower($formatSearch[$i])) {
                    $stateAbbr['state'] = strtolower($state);
                    $stateAbbr['stateCode'] = $abbr;
                    $stateAbbr['position'] = $i;
                    return $stateAbbr;
                }
            }
        }
        return null;

    }

    /**
     * @param $formatSearch
     * @param bool|false $noCity
     * @return null|string
     */
    private function findStateDouble($formatSearch, $noCity = false)
    {
        $stateLast = array_pop($formatSearch);
        $stateFirst = array_pop($formatSearch);
        $twoWordState = $stateFirst.' '.$stateLast;
        $states = $this->stateData();

        foreach ($states as $state) {
            if (strtolower($state) == strtolower($twoWordState)) {
                if ($noCity) {
                    $results = $twoWordState;
                    return $results;
                } else {
                    $results['state'] = $twoWordState;
                    $results['city'] = implode(' ', $formatSearch);
                    return $results;
                }
            }
        }

        return null;

    }

    /**
     * @param $formatSearch
     * @return null
     */
    private function findWestVirginia($formatSearch)
    {
        $stateLast = array_pop($formatSearch);
        $stateFirst = array_pop($formatSearch);
        $twoWordState = $stateFirst.' '.$stateLast;
        if ($twoWordState == 'west virginia') {
            $results['state'] = $twoWordState;
            $results['city'] = implode(' ', $formatSearch);
            return $results;
        }

        return null;

    }

    /**
     * @return array
     */
    public function stateData()
    {
        return array(
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
            'AR' => 'Arkansas',
            'CA' => 'California',
            'CO' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'DC' => 'District Of Columbia',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'ID' => 'Idaho',
            'IL' => 'Illinois',
            'IN' => 'Indiana',
            'IA' => 'Iowa',
            'KS' => 'Kansas',
            'KY' => 'Kentucky',
            'LA' => 'Louisiana',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MA' => 'Massachusetts',
            'MI' => 'Michigan',
            'MN' => 'Minnesota',
            'MS' => 'Mississippi',
            'MO' => 'Missouri',
            'MT' => 'Montana',
            'NE' => 'Nebraska',
            'NV' => 'Nevada',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NM' => 'New Mexico',
            'NY' => 'New York',
            'NC' => 'North Carolina',
            'ND' => 'North Dakota',
            'OH' => 'Ohio',
            'OK' => 'Oklahoma',
            'OR' => 'Oregon',
            'PA' => 'Pennsylvania',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'UT' => 'Utah',
            'VT' => 'Vermont',
            'VA' => 'Virginia',
            'WA' => 'Washington',
            'WV' => 'West Virginia',
            'WI' => 'Wisconsin',
            'WY' => 'Wyoming',
        );
    }

    /**
     * @return array
     */
    public function provinceData()
    {
        return array(
            //Provinces of Canada
            'AB' => 'Alberta',
            'LB' => 'Labrador',
            'NB' => 'New Brunswick',
            'NS' => 'Nova Scotia',
            'NW' => 'Northwest Territory',
            'PE' => 'Prince Edward Island',
            'SK' => 'Saskatchewen',
            'BC' => 'British Columbia',
            'MB' => 'Manitoba',
            'NF' => 'Newfoundland',
            'NU' => 'Nunavut',
            'ON' => 'Ontario',
            'QC' => 'Quebec',
            'YU' => 'Yukon',
        );
    }

    /**
     * @return array
     */
    public function stateProvinceData()
    {
        return array(
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
            'AR' => 'Arkansas',
            'CA' => 'California',
            'CO' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'DC' => 'District Of Columbia',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'ID' => 'Idaho',
            'IL' => 'Illinois',
            'IN' => 'Indiana',
            'IA' => 'Iowa',
            'KS' => 'Kansas',
            'KY' => 'Kentucky',
            'LA' => 'Louisiana',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MA' => 'Massachusetts',
            'MI' => 'Michigan',
            'MN' => 'Minnesota',
            'MS' => 'Mississippi',
            'MO' => 'Missouri',
            'MT' => 'Montana',
            'NE' => 'Nebraska',
            'NV' => 'Nevada',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NM' => 'New Mexico',
            'NY' => 'New York',
            'NC' => 'North Carolina',
            'ND' => 'North Dakota',
            'OH' => 'Ohio',
            'OK' => 'Oklahoma',
            'OR' => 'Oregon',
            'PA' => 'Pennsylvania',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'UT' => 'Utah',
            'VT' => 'Vermont',
            'VA' => 'Virginia',
            'WA' => 'Washington',
            'WV' => 'West Virginia',
            'WI' => 'Wisconsin',
            'WY' => 'Wyoming',
            //Provinces of Canada
            'AB' => 'Alberta',
            'LB' => 'Labrador',
            'NB' => 'New Brunswick',
            'NS' => 'Nova Scotia',
            'NW' => 'Northwest Territory',
            'PE' => 'Prince Edward Island',
            'SK' => 'Saskatchewen',
            'BC' => 'British Columbia',
            'MB' => 'Manitoba',
            'NF' => 'Newfoundland',
            'NU' => 'Nunavut',
            'ON' => 'Ontario',
            'QC' => 'Quebec',
            'YU' => 'Yukon',
        );
    }

    /**
     * @param $title
     * @return null|string
     */
    public function formatSearchTitle($title)
    {

        $formatTitle = str_replace(" ", "-", $title);
        $formatSearch = explode('-', strtolower($formatTitle));
        $searchTitle = $title;

        switch (count($formatSearch)) {
            case 1:
                break;
            case 2:
                $single = $this->findSingleState($formatSearch);
                if ($single) {
                    $searchTitle = $single;
                } else {
                    $double = $this->findDoubleState($formatSearch, true);
                    if ($double) {
                        $searchTitle = $double;
                    }
                }

                break;
            default:
                $single = $this->findSingleState($formatSearch);
                if ($single) {
                    $searchTitle = $single;
                } else {
                    $double = $this->findDoubleState($formatSearch);
                    if ($double) {
                        $searchTitle = $double;
                    }
                }
                break;
        }

        return $searchTitle;
    }

    /**
     * @param $formatSearch
     * @return null|string
     */
    private function findSingleState($formatSearch)
    {
        $states = $this->stateData();
        foreach ($states as $state) {
            $city = [];
            for ($i=0; $i < count($formatSearch); $i++) {
                if (strtolower($state) == strtolower($formatSearch[$i])) {
                    $results = '';
                    foreach ($city as $c) {
                        $results .= ' '.$c;
                    }
                    $results .= ', '.$formatSearch[$i];
                    return $results;
                } else {
                    $city[] = $formatSearch[$i];
                }
            }
        }

        return null;


    }

    /**
     * @param $formatSearch
     * @param bool|false $noCity
     * @return null|string
     */
    private function findDoubleState($formatSearch, $noCity = false)
    {
        $stateLast = array_pop($formatSearch);
        $stateFirst = array_pop($formatSearch);
        $twoWordState = $stateFirst.' '.$stateLast;
        $states = $this->stateData();

        foreach ($states as $state) {
            $city = [];
            if (strtolower($state) == strtolower($twoWordState)) {
                if ($noCity) {
                    $results = $twoWordState;
                    return $results;
                } else {
                    for ($i=0; $i < count($formatSearch); $i++) {
                        $city[] = $formatSearch[$i];
                    }
                    $results = '';
                    foreach ($city as $c) {
                        $results .= ' '.$c;
                    }
                    $results .= ', '.$twoWordState;
                    return $results;
                }
            }
        }

        return null;

    }

    /**
     * @return array
     */
    public function stateDescriptions()
    {
        return array(
            'AL' => 'Save On Last-Minute Travel to Alabama. From the white-sand beaches of the Gulf Coast to the lively
                college football stadiums of Auburn and Tuscaloosa, the Heart of Dixie offers something for everyone.
                For travelers, the metropolitan areas of Alabama offer a rich history of civil rights and space
                exploration. Plus, when you save on a great hotel in Alabama, you’ll have more room in your budget for
                platefuls of authentic Southern soul food. Find the best hotel rates to this vacation destination by
                searching below.',
            'AK' => 'Save On Last-Minute Travel to Alaska. Discover the majesty of America’s northern frontier. When
                it comes to budget travel in the U.S., Alaska offers unmatched beauty and adventure. From the stunning
                vistas of seven national parks to the welcoming atmosphere in the cities and towns, your visit to
                Alaska will be unforgettable. Visitors in the winter months experience outdoor sports by day and
                northern lights by night, while summertime travelers make the most of the extended daylight with
                countless outdoor activities to enjoy.',
            'AZ' => 'Save On Last-Minute Travel to Arizona. You won’t believe the wonders to be found in the Grand
                Canyon State! Arizona offers something for every type of visitor. Active travelers will enjoy the
                sporting events like the Cactus League Spring Training games and year-round championship golfing as
                well as the geological wonders like the Grand Canyon, Monument Valley and the Petrified Forest. Do not
                miss the exciting attractions in Phoenix, the inspiring retreat of Sedona, and the old western mining
                towns throughout the state.',
            'AR' => 'Save On Last-Minute Travel to Arkansas. There’s so much to explore in Arkansas. It’s called
                “The Natural State” for good reason, with tons of parks and wilderness areas to explore. From the
                soothing waters of Hot Springs National Park to the glistening caverns of the Ozarks, Arkansas offers
                beautiful natural wonders. Whether you’re planning a road trip throughout the diverse state or
                enjoying a stay in one of Arkansas’s quaint towns, you can save on your hotel stay by using
                HotelCoupons.com.',
            'CA' => 'Save on Last-Minute Travel to California. Whether you’re looking for the glitz and glamour of
                Beverly Hills or an outdoor adventure in California’s 118 state parks, you can get a great last-minute
                hotel deal to explore all that the Golden State has to offer. Feed the giraffes at the world-renowned
                San Diego zoo, drive the iconic Pacific Coast Highway and cross the Golden Gate Bridge without worrying
                about breaking the budget when you arrive to your California destination',
            'CO' => 'Save on Last-Minute Travel to Colorado. Experience the scenic beauty and outdoor activities that
                Colorado has to offer any time of year with a great last-minute hotel deal. Skiing, snowboarding,
                skating, sledding, and snowmobiling are just a few ways you can play in the winter months. For
                adventures without the snow, try hiking the scenic landscape or enjoy a music festival at Red Rocks
                Amphitheatre. Wherever your Colorado journey takes you, be sure to get a great hotel deal along the
                way. ',
            'CT' => 'Save on Last-Minute Travel to Connecticut. From the historic seaport of Mystic to the cultural
                sites in Hartford and the vineyards of Litchfield, Connecticut has a lot to offer discerning travelers.
                It’s not just quaint small towns and fall foliage, though. Try your luck at the Foxwoods – MGM Grand
                Resort and Mohegan Sun Casino or take the thrill-seekers in your party to the zipline Adventure Park
                in Storrs. Whether you want to drive Merritt Parkway through the charming towns or explore the rich
                nightlife in the cities, there are unbeatable last-minute hotel deals waiting for you.',
            'DE' => '',
            'DC' => '',
            'FL' => '',
            'GA' => '',
            'HI' => '',
            'ID' => '',
            'IL' => '',
            'IN' => '',
            'IA' => '',
            'KS' => '',
            'KY' => '',
            'LA' => '',
            'ME' => '',
            'MD' => '',
            'MA' => '',
            'MI' => '',
            'MN' => '',
            'MS' => '',
            'MO' => '',
            'MT' => '',
            'NE' => '',
            'NV' => '',
            'NH' => '',
            'NJ' => '',
            'NM' => '',
            'NY' => '',
            'NC' => '',
            'ND' => '',
            'OH' => '',
            'OK' => '',
            'OR' => '',
            'PA' => '',
            'RI' => '',
            'SC' => '',
            'SD' => '',
            'TN' => '',
            'TX' => '',
            'UT' => '',
            'VT' => '',
            'VA' => '',
            'WA' => '',
            'WV' => '',
            'WI' => '',
            'WY' => '',

        );
    }

    /**
     * @param $results
     * @return array
     */
    public function formatGoogleParams($results)
    {
        $stateList = $this->stateProvinceData();
        $search = $results['search'];
        $searchList = explode(",", $search);
        $searchList = array_reverse($searchList);
        $formatResults = array(
            'place' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'search' => '',
            'lat' => '',
            'lon' => '',
            'stateCode' => '',
            'searchType' => '',
            'country' => '',
        );

        if (!empty($results['lat'])) {
            $lat = trim($results['lat']);
            $formatResults['lat'] = $lat;
        } else {
            $formatResults['searchType'] = 'error';
            return $formatResults;
        }

        if (!empty($results['lon'])) {
            $lon = trim($results['lon']);
            $formatResults['lon'] = $lon;
        } else {
            $formatResults['searchtype'] = 'error';
            return $formatResults;
        }

        if (!empty($results['state'])) {
            $state = ucwords(trim($results['state']));
            $formatResults['state'] = $state;
            $stateCode = array_search($state, $stateList);
            if ($stateCode) {
                $formatResults['stateCode'] = $stateCode;
            }
        }

        if (!empty($results['zip'])) {
            $zip = trim($results['zip']);
            $formatResults['zip'] = $zip;
        }

        if (!empty($results['city'])) {
            $city = ucwords(trim($results['city']));
            $formatResults['city'] = $city;
        }

        switch (count($searchList)) {
            case 5:
                // State
                if (empty($formatResults['state']) || empty($formatResults['stateCode'])) {
                    $state = ucwords(trim($searchList[1]));
                    if (array_key_exists($state, $stateList)) {
                        $formatResults['stateCode'] = $state;
                        $formatResults['state'] = $stateList[$state];
                    } else {
                        $formatresults['searchtype'] = 'error';
                        return $formatresults;
                    }
                }

                // City
                if (empty($formatResults['city'])) {
                    $city = ucwords(trim($searchList[2]));
                    $formatResults['city'] = $city;
                }

                // Country
                $country = $this->getCountry($searchList);
                if ($country) {
                    $formatResults['country'] = $country;
                } else {
                    $formatResults['searchtype'] = 'error';
                    return $formatResults;
                }

                // Place
                $place = ucwords(trim($searchList[4]));
                $validPlaces = explode(" ", $place);
                if (is_numeric($validPlaces[0])) {
                    $formatResults['searchType'] = 'city';
                } else {
                    $formatResults['place'] = $place;
                    $formatResults['searchType'] = 'place';
                }
                break;

            case 4:
                // State
                if (empty($formatResults['state']) || empty($formatResults['stateCode'])) {
                    $state = ucwords(trim($searchList[1]));
                    if (array_key_exists($state, $stateList)) {
                        $formatResults['stateCode'] = $state;
                        $formatResults['state'] = $stateList[$state];
                    } else {
                        $formatResults['searchType'] = 'error';
                        return $formatResults;
                    }
                }

                // City
                if (empty($formatResults['city'])) {
                    $city = ucwords(trim($searchList[2]));
                    $formatResults['city'] = $city;
                }

                // Country
                $country = $this->getCountry($searchList);
                if ($country) {
                    $formatResults['country'] = $country;
                } else {
                    $formatResults['searchtype'] = 'error';
                    return $formatResults;
                }

                // Place
                $place = ucwords(trim($searchList[3]));
                $validPlaces = explode(" ", $place);
                if (is_numeric($validPlaces[0])) {
                    $formatResults['searchType'] = 'city';
                } else {
                    $formatResults['place'] = $place;
                    $formatResults['searchType'] = 'place';
                }
                break;

            case 3:
                // State
                if (empty($formatResults['state']) || empty($formatResults['stateCode'])) {
                    $state = ucwords(trim($searchList[1]));
                    if (array_key_exists($state, $stateList)) {
                        $formatResults['stateCode'] = $state;
                        $formatResults['state'] = $stateList[$state];
                    } else {
                        $formatResults['searchType'] = 'error';
                        return $formatResults;
                    }
                }

                // City
                if (empty($formatResults['city'])) {
                    $city = ucwords(trim($searchList[2]));
                    $formatResults['city'] = $city;
//                    $formatResults['searchType'] = 'error';
//                    return $formatResults;
                }

                // Country
                $country = $this->getCountry($searchList);
                if ($country) {
                    $formatResults['country'] = $country;
                } else {
                    $formatResults['searchType'] = 'error';
                    return $formatResults;
                }

                $formatResults['searchType'] = 'city';
                break;

            case 2:
                // State
                if (empty($formatResults['state']) || empty($formatResults['stateCode'])) {
                    $state = ucwords(trim($searchList[1]));
                    $formatResults['state'] = $state;
                    $stateCode = array_search($state, $stateList);
                    if ($stateCode) {
                        $formatResults['stateCode'] = $stateCode;
                    } else {
                        $formatResults['searchType'] = 'error';
                        return $formatResults;
                    }
                }
                // Country
                $country = $this->getCountry($searchList);
                if ($country) {
                    $formatResults['country'] = $country;
                } else {
                    $formatResults['searchType'] = 'error';
                    return $formatResults;
                }

                $formatResults['searchType'] = 'state';
                break;

            default:
                $formatResults['searchType'] = 'error';
                break;
        }
        return $formatResults;
    }

    /**
     * @param $searchList
     * @return bool|string
     */
    private function getCountry($searchList)
    {
        $country = ucwords(trim($searchList[0]));
        if ($country == 'United States') {
            return 'US';
        } elseif ($country == 'Canada') {
            return 'CA';
        } else {
            return false;
        }
    }

    /**
     * @param $results
     * @return array
     */
    public function parseFeatured($results)
    {
        $closest = [];
        $desktopClosest = [];
        $desktopRandom = [];
        $random = [];
        $extraRandom = [];
        $desktopExtraRandom = [];
        $i = 0;
        foreach ($results as $r) {
            if ($i == 0) {
                $closest['result'] = $r[0];
                $closest['distance'] = round($r['distance'], 1);
            }

            if ($i == 1) {
                $desktopClosest['result'] = $r[0];
                $desktopClosest['distance'] = round($r['distance'], 1);

                $dist = round($r['distance'], 1);
                if ($dist <= 20) {
                    $random[] = array(
                        'result' => $r[0],
                        'distance' => $dist,
                    );
                } else {
                    $extraRandom[] = array(
                        'result' => $r[0],
                        'distance' => $dist,
                    );
                }
            }

            if ($i > 1) {
                $dist = round($r['distance'], 1);
                if ($dist <= 20) {
                    $random[] = array(
                        'result' => $r[0],
                        'distance' => $dist,
                    );
                    $desktopRandom[] = array(
                        'result' => $r[0],
                        'distance' => $dist,
                    );
                } else {
                    $extraRandom[] = array(
                        'result' => $r[0],
                        'distance' => $dist,
                    );
                    $desktopExtraRandom[] = array(
                        'result' => $r[0],
                        'distance' => $dist,
                    );
                }
            }
            $i++;
        }
        shuffle($desktopRandom);
        shuffle($desktopExtraRandom);
        shuffle($random);
        shuffle($extraRandom);
        return array(
            'closest' => $closest,
            'desktopClosest' => $desktopClosest,
            'desktopRandom' => $desktopRandom,
            'desktopExtraRandom' => $desktopExtraRandom,
            'random' => $random,
            'extraRandom' => $extraRandom,
        );
    }
}
