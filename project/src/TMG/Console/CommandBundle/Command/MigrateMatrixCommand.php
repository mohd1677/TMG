<?php

namespace TMG\Console\CommandBundle\Command;

use PDO;
use DateTime;
use Doctrine\ORM\EntityManager;
use TMG\Api\ApiBundle\Entity\Rate;
use TMG\Api\UserBundle\Entity\User;
use TMG\Api\ApiBundle\Entity\Video;
use TMG\Api\ApiBundle\Entity\Books;
use TMG\Api\ApiBundle\Entity\Brand;
use TMG\Api\ApiBundle\Entity\Photo;
use TMG\Api\ApiBundle\Entity\Social;
use TMG\Api\ApiBundle\Entity\States;
use TMG\Api\ApiBundle\Entity\Address;
use TMG\Api\ApiBundle\Entity\CallLog;
use TMG\Api\ApiBundle\Entity\Country;
use TMG\Api\ApiBundle\Entity\TollFree;
use TMG\Api\ApiBundle\Entity\RateType;
use TMG\Api\ApiBundle\Entity\SalesRep;
use TMG\Api\ApiBundle\Entity\Analytic;
use TMG\Api\ApiBundle\Entity\Products;
use TMG\Api\ApiBundle\Entity\Property;
use TMG\Api\ApiBundle\Entity\Activity;
use TMG\Api\ApiBundle\Entity\Contract;
use TMG\Api\ApiBundle\Entity\Amenities;
use TMG\Api\ApiBundle\Entity\FormDatas;
use TMG\Api\ApiBundle\Entity\DeviceType;
use TMG\Api\ApiBundle\Entity\PostalCode;
use TMG\Api\ApiBundle\Entity\CityCenter;
use TMG\Api\ApiBundle\Entity\SocialData;
use TMG\Api\ApiBundle\Entity\IHGProperty;
use TMG\Api\ApiBundle\Entity\TravelTypes;
use TMG\Api\ApiBundle\Entity\SpecialType;
use TMG\Api\ApiBundle\Entity\Description;
use TMG\Api\ApiBundle\Entity\VideoStatus;
use TMG\Api\ApiBundle\Entity\ProductTypes;
use TMG\Api\ApiBundle\Entity\Confirmation;
use TMG\Api\ApiBundle\Entity\SocialDataType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class MigrateMatrixCommand extends ContainerAwareCommand
{
    //Global
    protected $em;
    protected $container;
    protected $pdo;
    protected $dbHost;
    protected $dbName;
    protected $dbUser;
    protected $dbPass;
    protected $uManager;

    // Repos
    private $repRepo;
    private $stateRepo;
    private $addressRepo;
    private $amenitiesRepo;
    private $bookRepo;
    private $formsRepo;
    private $typesRepo;
    private $productsRepo;
    private $productTypesRepo;
    private $propertyRepo;
    private $descriptionRepo;
    private $videoStatusRepo;
    private $videoRepo;
    private $userRepo;
    private $activityRepo;
    private $deviceTypeRepo;
    private $analyticRepo;
    private $confirmationRepo;
    private $contractRepo;
    private $callLogRepo;
    private $specialTypeRepo;
    private $countryRepo;
    private $postalCodeRepo;
    private $cityCenterRepo;
    private $ihgRepo;
    private $rateTypeRepo;
    private $rateRepo;
    private $photoRepo;
    private $socialDataTypeRepo;
    private $socialDataRepo;
    private $socialRepo;
    private $tollFreeRepo;
    private $brandRepo;

    // Classes
    protected $userClass= 'TMG\Api\UserBundle\Entity\User';

    protected $repClass = 'TMG\Api\ApiBundle\Entity\SalesRep';
    protected $stateClass = 'TMG\Api\ApiBundle\Entity\State';
    protected $addressClass= 'TMG\Api\ApiBundle\Entity\Address';
    protected $amenitiesClass= 'TMG\Api\ApiBundle\Entity\Amenities';
    protected $bookClass= 'TMG\Api\ApiBundle\Entity\Books';
    protected $formsClass= 'TMG\Api\ApiBundle\Entity\FormDatas';
    protected $typesClass= 'TMG\Api\ApiBundle\Entity\TravelTypes';
    protected $productsClass= 'TMG\Api\ApiBundle\Entity\Products';
    protected $productTypesClass= 'TMG\Api\ApiBundle\Entity\ProductTypes';
    protected $propertyClass= 'TMG\Api\ApiBundle\Entity\Property';
    protected $descriptionClass= 'TMG\Api\ApiBundle\Entity\Description';
    protected $videoStatusClass= 'TMG\Api\ApiBundle\Entity\VideoStatus';
    protected $videoClass= 'TMG\Api\ApiBundle\Entity\Video';
    protected $activityClass= 'TMG\Api\ApiBundle\Entity\Activity';
    protected $deviceTypeClass= 'TMG\Api\ApiBundle\Entity\DeviceType';
    protected $analyticClass= 'TMG\Api\ApiBundle\Entity\Analytic';
    protected $confirmationClass= 'TMG\Api\ApiBundle\Entity\Confirmation';
    protected $contractClass= 'TMG\Api\ApiBundle\Entity\Contract';
    protected $callLogClass= 'TMG\Api\ApiBundle\Entity\CallLog';
    protected $specialTypeClass= 'TMG\Api\ApiBundle\Entity\SpecialType';
    protected $countryClass= 'TMG\Api\ApiBundle\Entity\Country';
    protected $postalCodeClass= 'TMG\Api\ApiBundle\Entity\PostalCode';
    protected $cityCenterClass= 'TMG\Api\ApiBundle\Entity\CityCenter';
    protected $ihgClass= 'TMG\Api\ApiBundle\Entity\IHGProperty';
    protected $rateTypeClass= 'TMG\Api\ApiBundle\Entity\RateType';
    protected $rateClass= 'TMG\Api\ApiBundle\Entity\Rate';
    protected $photoClass= 'TMG\Api\ApiBundle\Entity\Photo';
    protected $socialDataTypeClass= 'TMG\Api\ApiBundle\Entity\SocialDataType';
    protected $socialDataClass= 'TMG\Api\ApiBundle\Entity\SocialData';
    protected $socialClass= 'TMG\Api\ApiBundle\Entity\Social';
    protected $tollFreeClass= 'TMG\Api\ApiBundle\Entity\TollFree';
    protected $brandClass= 'TMG\Api\ApiBundle\Entity\Brand';

    protected function configure()
    {
        $this
            ->setName('migrate:migrate-matrix')
            ->setDescription('Get Data from OG Matrix DB')
            ->addArgument(
                'table',
                InputArgument::OPTIONAL,
                'specifiy table to update'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init();

        $table = $input->getArgument('table');
        $table = strtolower($table);

        switch ($table) {
            case 'address':
            case 'addresses':
                $output->writeLn('<question>Getting Addresses...</question>');
                $this->getAddresses();
                $output->writeLn('<info>Finished Addresses</info>');
                break;
            case 'amenity':
            case 'amenities':
                $output->writeLn('<question>Getting Amenities...</question>');
                $this->getAmenities();
                $output->writeLn('<info>Finished Amenities</info>');
                break;
            case 'formdata':
            case 'formdatas':
                $output->writeLn('<question>Getting Form Data...</question>');
                $this->getForms();
                $output->writeLn('<info>Finished Forms</info>');
                break;
            case 'description':
            case 'descriptions':
                $output->writeLn('<question>Getting Descriptions...</question>');
                $this->getDescriptions();
                $output->writeLn('<info>Finished Descriptions</info>');
                break;
            case 'video':
            case 'videos':
                $output->writeLn('<question>Getting Videos...</question>');
                $this->getVideos();
                $output->writeLn('<info>Finished Videos</info>');
                break;
            case 'activity':
            case 'activities':
                // TODO: Need Users First
                $output->writeLn('<question>Getting Activities...</question>');
                $this->getActivities();
                $output->writeLn('<info>Finished Activities</info>');
                break;
            case 'analytic':
            case 'analytics':
                $output->writeLn('<question>Getting Analytics...</question>');
                $this->getAnalytics();
                $output->writeLn('<info>Finished Analytics</info>');
                break;
            case 'confirmation':
            case 'confirmations':
                $output->writeLn('<question>Getting Confirmations...</question>');
                $this->getConfirmations();
                $output->writeLn('<info>Finished Confirmations</info>');
                break;
            case 'call':
            case 'calls':
            case 'calllog':
            case 'calllogs':
                $output->writeLn('<question>Getting Calls...</question>');
                $this->getCalls();
                $output->writeLn('<info>Finished Calls</info>');
                break;
            case 'zip':
            case 'zips':
            case 'zipcode':
            case 'zipcodes':
            case 'postalcode':
            case 'postalcodes':
                $output->writeLn('<question>Getting Postal Codes...</question>');
                $this->getPostalCodes();
                $output->writeLn('<info>Finished PostalCodes</info>');
                break;
            case 'city':
            case 'cities':
            case 'citycenter':
            case 'citycenters':
                $output->writeLn('<question>Getting City Centers...</question>');
                $this->getCityCenters();
                $output->writeLn('<info>Finished CityCenters</info>');
                break;
            case 'ihg':
            case 'ihgs':
                $output->writeLn('<question>Getting IHG Properties...</question>');
                $this->getIhg();
                $output->writeLn('<info>Finished IHG Properties</info>');
                break;
            case 'advertisement':
            case 'advertisements':
            case 'rate':
            case 'rates':
                $output->writeLn('<question>Getting Rates...</question>');
                $this->getRates();
                $output->writeLn('<info>Finished Rates</info>');
                break;
            case 'photo':
            case 'photos':
                $output->writeLn('<question>Getting Photos...</question>');
                $this->getPhotos();
                $output->writeLn('<info>Finished Photos</info>');
                break;
            case 'social':
            case 'socials':
                $output->writeLn('<question>Getting Social Data...</question>');
                $this->getSocial();
                $output->writeLn('<info>Finished Social Data</info>');
                break;
            case 'tollfree':
            case 'tollfrees':
                $output->writeLn('<question>Getting Toll Free Numbers...</question>');
                $this->getTollFrees();
                $output->writeLn('<info>Finished Toll Free Numbers</info>');
                break;
            case 'user':
            case 'users':
                $output->writeLn('<error>Use `app/console migrate:users` to migrate users</error>');
                break;
            case 'done':
                // Sales Reps
                $output->writeLn('<question>Getting Sales Reps....</question>');
                $this->getSalesReps();
                $output->writeLn('<info>Finished Sales Reps</info>');

                // States
                $output->writeLn('<question>Importing states...</question>');
                $this->importStates();
                $output->writeLn('<info>Finished states</info>');

                // Books
                $output->writeLn('<question>Getting Books...</question>');
                $this->getBooks();
                $output->writeLn('<info>Finished Books</info>');

                // Travel Types
                $output->writeLn('<question>Getting Travel Types...</question>');
                $this->getTravelTypes();
                $output->writeLn('<info>Finished Travel Types</info>');

                // Special Types
                $output->writeLn('<question>Getting Special Types...</question>');
                $this->getSpecialTypes();
                $output->writeLn('<info>Finished Special Types</info>');

                // Countries
                $output->writeLn('<question>Getting Countries...</question>');
                $this->getCountries();
                $output->writeLn('<info>Finished Countries</info>');

                // Setup Amenities
                $output->writeLn('<question>Getting Amenities List...</question>');
                $this->setupAmenities();
                $output->writeLn('<info>Finished Amenities List</info>');

                // Products
                $output->writeLn('<question>Getting Products...</question>');
                $this->getProducts();
                $output->writeLn('<info>Finished Products</info>');

                // Properties
                $output->writeLn('<question>Getting Properties...</question>');
                $this->getProperties();
                $output->writeLn('<info>Finished Properties</info>');
                break;
            default:
                /* =============================== */
                /* The Order of these is important */
                /* >>>>>>>> Do Not Change <<<<<<<< */
                /* =============================== */
                // Postal Codes
                $output->writeLn('<question>Getting Postal Codes...</question>');
                $this->getPostalCodes();
                $output->writeLn('<info>Finished PostalCodes</info>');

                // Addresses
                $output->writeLn('<question>Getting Addresses...</question>');
                $this->getAddresses();
                $output->writeLn('<info>Finished Addresses</info>');

                // Amenities
                $output->writeLn('<question>Getting Amenities...</question>');
                $this->getAmenities();
                $output->writeLn('<info>Finished Amenities</info>');

                // Descriptions
                $output->writeLn('<question>Getting Descriptions...</question>');
                $this->getDescriptions();
                $output->writeLn('<info>Finished Descriptions</info>');

                // Photos
                $output->writeLn('<question>Getting Photos...</question>');
                $this->getPhotos();
                $output->writeLn('<info>Finished Photos</info>');

                // Toll Free
                $output->writeLn('<question>Getting Toll Free Numbers...</question>');
                $this->getTollFrees();
                $output->writeLn('<info>Finished Toll Free Numbers</info>');

                // Videos
                $output->writeLn('<question>Getting Videos...</question>');
                $this->getVideos();
                $output->writeLn('<info>Finished Videos</info>');

                // Confirmations
                $output->writeLn('<question>Getting Confirmations...</question>');
                $this->getConfirmations();
                $output->writeLn('<info>Finished Confirmations</info>');

                // Activities
                $output->writeLn('<question>Getting Videos...</question>');
                $this->getActivities();
                $output->writeLn('<info>Finished Videos</info>');

                // Calls
                $output->writeLn('<question>Getting Calls...</question>');
                $this->getCalls();
                $output->writeLn('<info>Finished Calls</info>');

                // Analytics
                $output->writeLn('<question>Getting Analytics...</question>');
                $this->getAnalytics();
                $output->writeLn('<info>Finished Analytics</info>');

                // Social
                $output->writeLn('<question>Getting Social Data...</question>');
                $this->getSocial();
                $output->writeLn('<info>Finished Social Data</info>');

                // IHG
                $output->writeLn('<question>Getting IHG Properties...</question>');
                $this->getIhg();
                $output->writeLn('<info>Finished IHG Properties</info>');

                // City Centers
                $output->writeLn('<question>Getting City Centers...</question>');
                $this->getCityCenters();
                $output->writeLn('<info>Finished CityCenters</info>');

                // Form Datas
                $output->writeLn('<question>Getting Form Data...</question>');
                $this->getForms();
                $output->writeLn('<info>Finished Forms</info>');

                // Rates
                /* TODO: Need Users First
                /* FIXME: Duplicating Rates
                $output->writeLn('<question>Getting Rates...</question>');
                $this->getRates();
                $output->writeLn('<info>Finished Rates</info>');
                */
                break;
        }
    }

    private function init()
    {
        $this->container = $this->getContainer();
        $this->em = $this->container->get('doctrine')->getManager();
        $this->dbHost = $this->container->getParameter('matrix_db_host');
        $this->dbName = $this->container->getParameter('matrix_db_name');
        $this->dbUser = $this->container->getParameter('matrix_db_user');
        $this->dbPass = $this->container->getParameter('matrix_db_pass');
        $this->uManager = $this->container->get('fos_user.user_manager');

        //Repos
        $this->repRepo = $this->em->getRepository('ApiBundle:SalesRep');
        $this->stateRepo = $this->em->getRepository('ApiBundle:State');
        $this->addressRepo = $this->em->getRepository('ApiBundle:Address');
        $this->amenitiesRepo = $this->em->getRepository('ApiBundle:Amenities');
        $this->bookRepo = $this->em->getRepository('ApiBundle:Books');
        $this->formsRepo = $this->em->getRepository('ApiBundle:FormDatas');
        $this->typesRepo = $this->em->getRepository('ApiBundle:TravelTypes');
        $this->productsRepo = $this->em->getRepository('ApiBundle:Products');
        $this->productTypesRepo = $this->em->getRepository('ApiBundle:ProductTypes');
        $this->propertyRepo =$this->em->getRepository('ApiBundle:Property');
        $this->descriptionRepo = $this->em->getRepository('ApiBundle:Description');
        $this->videoStatusRepo = $this->em->getRepository('ApiBundle:VideoStatus');
        $this->videoRepo = $this->em->getRepository('ApiBundle:Video');
        $this->userRepo = $this->em->getRepository('ApiUserBundle:User');
        $this->activityRepo = $this->em->getRepository('ApiBundle:Activity');
        $this->deviceTypeRepo = $this->em->getRepository('ApiBundle:DeviceType');
        $this->analyticRepo = $this->em->getRepository('ApiBundle:Analytic');
        $this->contractRepo = $this->em->getRepository('ApiBundle:Contract');
        $this->confirmationRepo = $this->em->getRepository('ApiBundle:Confirmation');
        $this->callLogRepo = $this->em->getRepository('ApiBundle:CallLog');
        $this->specialTypeRepo = $this->em->getRepository('ApiBundle:SpecialType');
        $this->countryRepo = $this->em->getRepository('ApiBundle:Country');
        $this->postalCodeRepo = $this->em->getRepository('ApiBundle:PostalCode');
        $this->cityCenterRepo = $this->em->getRepository('ApiBundle:CityCenter');
        $this->ihgRepo = $this->em->getRepository('ApiBundle:IHGProperty');
        $this->rateTypeRepo = $this->em->getRepository('ApiBundle:RateType');
        $this->rateRepo = $this->em->getRepository('ApiBundle:Rate');
        $this->photoRepo = $this->em->getRepository('ApiBundle:Photo');
        $this->socialDataTypeRepo = $this->em->getRepository('ApiBundle:SocialDataType');
        $this->socialDataRepo = $this->em->getRepository('ApiBundle:SocialData');
        $this->socialRepo = $this->em->getRepository('ApiBundle:Social');
        $this->tollFreeRepo = $this->em->getRepository('ApiBundle:TollFree');
        $this->brandRepo = $this->em->getRepository('ApiBundle:Brand');

        $dsn = 'mysql:host='.$this->dbHost.';dbname='.$this->dbName;
        $this->pdo = new PDO($dsn, $this->dbUser, $this->dbPass);
    }



    /*
     * Property Amenities
     */
    private function getAmenities()
    {
        // Property Amenities
        $this->em->flush();
        $this->em->clear();

        ladybug_dump('Amenities Finished...Mapping Property Amenities');

        $min = 0;
        $max = 100;
        $count = $this->propertyRepo->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();


        do {
            $newAmenities = $this->amenitiesRepo->findAll();
            $idFinder = $this->pdo->prepare(
                "SELECT amenities_id
                FROM properties_amenities
                WHERE properties_id = :id"
            );

            $properties = $this->propertyRepo->createQueryBuilder('p')
                ->select('p.hash')
                ->setFirstResult($min)
                ->setMaxResults($max)
                ->getQuery()
                ->getResult();

            foreach ($properties as $prop) {
                $hash = $prop['hash'];

                $idFinder->bindvalue(':id', $hash);

                // There's an actual error with the query or database.
                if (!$idFinder->execute()) {
                    ladybug_dump($idFinder->errorInfo());
                    exit(1);
                }

                $amenities = $idFinder->fetchAll(PDO::FETCH_ASSOC);
                if ($amenities) {
                    $property = $this->propertyRepo->findOneBy(array(
                        'hash'=> $hash,
                    ));
                    $setAmenities = new ArrayCollection();
                    foreach ($amenities as $a) {
                        $id = $a['amenities_id'];
                        $old = $amenitiesList[$id];
                        foreach ($newAmenities as $na) {
                            if ($na->getKeySelector() == $old) {
                                $setAmenities[] = $na;
                            }
                        }
                        echo '+';
                    }
                    $property->setAmenities($setAmenities);
                    $this->em->flush($property);
                }
            }

            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);
    }


    /*
     * Form Datas
     */
    private function getForms()
    {
        $min = 0;
        $max = 100;
        $currentId = $this->formsRepo->createQueryBuilder('f')
            ->select('COUNT(f.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $cFinder = $this->pdo->prepare(
            'SELECT COUNT(*) FROM form_datas
             WHERE id > :current'
        );
        $cFinder->bindvalue(':current', (int) $currentId, PDO::PARAM_INT);

        // There's an actual error with the query or database.
        if (!$cFinder->execute()) {
            ladybug_dump($cFinder->errorInfo());
            exit(1);
        }
        $count = $cFinder->fetchColumn();

        $result = $this->pdo->prepare(
            'SELECT * FROM form_datas
             WHERE id > :current
             LIMIT :max
             OFFSET :min'
        );

        do {
            $result->bindvalue(':current', (int) $currentId, PDO::PARAM_INT);
            $result->bindvalue(':max', (int) $max, PDO::PARAM_INT);
            $result->bindvalue(':min', (int) $min, PDO::PARAM_INT);

            // There's an actual error with the query or database.
            if (!$result->execute()) {
                ladybug_dump($result->errorInfo());
                exit(1);
            }

            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                if (@unserialize($row['form_data']) !== false) {
                    $data = unserialize($row['form_data']);
                } else {
                    $data = array();
                }
                $form = new $this->formsClass;
                $form->setFormName($row['form_name']);
                $form->setFormData($data);
                $this->em->persist($form);
                $this->em->flush();
                echo '+';
            }


            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);
    }




    /*
     * Addresses
     */
    private function getAddresses()
    {
        $min = 0;
        $max = 100;
        $count = $this->addressRepo->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.latitude is null')
            ->orWhere('a.longitude is null')
            ->getQuery()
            ->getSingleScalarResult();

        $result = $this->pdo->prepare(
            'SELECT *
            FROM addresses
            WHERE line1 = :line1
            AND city = :city
            AND state = :state
            AND zip = :zip
            AND country = :country'
        );

        do {
            $adds = $this->addressRepo->createQueryBuilder('a')
                ->select('
                    a.id,
                    a.line1,
                    a.city,
                    identity(a.state) as state,
                    identity(a.country) as country,
                    identity(a.postalCode) as postal')
                ->where('a.latitude is null')
                ->andWhere('a.longitude is null')
                ->setFirstResult($min)
                ->setMaxResults($max)
                ->getQuery()
                ->getResult();

            foreach ($adds as $a) {
                // State
                $abbr = null;
                $state = $this->stateRepo->findOneById($a['state']);
                if ($state) {
                    $abbr = $state->getAbbreviation();
                }

                // Country
                $cc = null;
                $country = $this->countryRepo->findOneById($a['country']);
                if ($state) {
                    $cc = $country->getCode();
                }

                // Postal
                $zip = null;
                $pc = $this->postalCodeRepo->findOneById($a['postal']);
                if ($pc) {
                    $zip = $pc->getCode();
                }

                $result->bindvalue(':line1', $a['line1']);
                $result->bindvalue(':city', $a['city']);
                $result->bindvalue(':state', $abbr);
                $result->bindvalue(':zip', $zip);
                $result->bindvalue(':country', $cc);

                // There's an actual error with the query or database.
                if (!$result->execute()) {
                    ladybug_dump($result->errorInfo());
                    exit(1);
                }

                $oldAdd = $result->fetch(PDO::FETCH_ASSOC);

                if (!$oldAdd) {
                    continue;
                }
                $lat = null;
                $lon = null;

                $lat = $oldAdd['latitude'];
                $lon = $oldAdd['longitude'];

                if ($lat && $lon) {
                    $address = $this->addressRepo->findOneById($a['id']);
                    $address->setLatitude($lat);
                    $address->setLongitude($lon);
                    $this->em->flush();
                    echo '+';
                }
            }

            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);
    }



    /*
     *  Descriptions
     */
    private function getDescriptions()
    {
        $min = 0;
        $max = 100;
        $count = $this->propertyRepo->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $findDesc = $this->pdo->prepare(
            'SELECT *
            FROM property_descriptions
            WHERE property_id = :id'
        );

        $findUrl = $this->pdo->prepare(
            'SELECT url
            FROM properties
            WHERE id = :id'
        );

        $findDisplay = $this->pdo->prepare(
            'SELECT url_small
            FROM property_photos
            WHERE property_id = :id
            AND is_display_img = :val'
        );

        $findBanner = $this->pdo->prepare(
            'SELECT url_extra_large
            FROM property_photos
            WHERE property_id = :id
            AND is_banner_img = :val'
        );

        do {
            $properties = $this->propertyRepo->createQueryBuilder('p')
                ->select('p.id, p.hash')
                ->setFirstResult($min)
                ->setMaxResults($max)
                ->getQuery()
                ->getResult();

            foreach ($properties as $p) {
                $pId = $p['id'];
                $pHash = $p['hash'];
                $hasData = false;

                // Find Url
                $findUrl->bindvalue(':id', $pHash);

                // There's an actual error with the query or database.
                if (!$findUrl->execute()) {
                    ladybug_dump($findUrl->errorInfo());
                    exit(1);
                }

                $url = $findUrl->fetch(PDO::FETCH_ASSOC);
                if ($url) {
                    $hasData = true;
                }

                // Find Desc
                $findDesc->bindvalue(':id', $pHash);

                // There's an actual error with the query or database.
                if (!$findDesc->execute()) {
                    ladybug_dump($findDesc->errorInfo());
                    exit(1);
                }

                $oldDesc = $findDesc->fetch(PDO::FETCH_ASSOC);
                if ($oldDesc) {
                    $hasData = true;
                }

                // Find Banner
                $findBanner->bindvalue(':id', $pHash);
                $findBanner->bindvalue(':val', (int) 1, PDO::PARAM_INT);

                // There's an actual error with the query or database.
                if (!$findBanner->execute()) {
                    ladybug_dump($findBanner->errorInfo());
                    exit(1);
                }

                $banner = $findBanner->fetch(PDO::FETCH_ASSOC);
                if ($banner) {
                    $hasData = true;
                }

                // Find Display
                $findDisplay->bindvalue(':id', $pHash);
                $findDisplay->bindvalue(':val', (int) 1, PDO::PARAM_INT);

                // There's an actual error with the query or database.
                if (!$findDisplay->execute()) {
                    ladybug_dump($findDisplay->errorInfo());
                    exit(1);
                }

                $display = $findDisplay->fetch(PDO::FETCH_ASSOC);
                if ($display) {
                    $hasData = true;
                }

                if ($hasData) {
                    $description = $this->descriptionRepo->findOneBy(array(
                        'property'=> $pId,
                    ));

                    if (!$description) {
                        $property = $this->propertyRepo->findOneById($pId);
                        $description = new $this->descriptionClass;
                        $description->setProperty($property);
                        $this->em->persist($description);
                    }
                    // Set Url
                    if ($url) {
                        $description->setUrl($url['url']);
                    }

                    // Set Old Description
                    if ($oldDesc) {
                        $description->setDescription($oldDesc['description']);
                        $description->setBriefDescription($oldDesc['brief_description']);
                        $description->setDirections($oldDesc['directions']);
                        $description->setRestrictions($oldDesc['restrictions']);
                    }

                    // Set Banner
                    if ($banner) {
                        $description->setBannerImage($banner['url_extra_large']);
                    }

                    // Set Display
                    if ($display) {
                        $description->setDisplayImage($display['url_small']);
                    }
                    echo '+';
                    $this->em->flush();
                }
            }

            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);
    }

    /*
     * Videos
     */
    private function getVideos()
    {
        // Video Statuses
        $statuses = $this->videoStatusList();
        foreach ($statuses as $vs) {
            $status = $this->videoStatusRepo->findOneBy(array(
                'name'=> $vs,
            ));

            if (!$status) {
                $status = new $this->videoStatusClass;
                $status->setName($vs);
                $this->em->persist($status);
                $this->em->flush();
                echo '+';
            }
        }

        // Videos
        $min = 0;
        $max = 100;
        $count = $this->propertyRepo->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $findId = $this->pdo->prepare(
            'SELECT video_id
            FROM properties
            WHERE id = :id'
        );

        $findVideo = $this->pdo->prepare(
            'SELECT *
            FROM videos
            WHERE id = :id'
        );



        do {
            $properties = $this->propertyRepo->createQueryBuilder('p')
                ->select('p.id, p.hash')
                ->setFirstResult($min)
                ->setMaxResults($max)
                ->getQuery()
                ->getResult();

            foreach ($properties as $p) {
                $pId = $p['id'];
                $pHash = $p['hash'];
                $hasVideo = false;

                // Find Video
                $findId->bindvalue(':id', $pHash);

                // There's an actual error with the query or database.
                if (!$findId->execute()) {
                    ladybug_dump($findId->errorInfo());
                    exit(1);
                }

                $videoId = $findId->fetch(PDO::FETCH_ASSOC);
                if ($videoId['video_id']) {
                    $videoId = $videoId['video_id'];
                    $hasVideo = true;
                }

                if ($hasVideo) {
                    // Find Video
                    $findVideo->bindvalue(':id', (int) $videoId, PDO::PARAM_INT);

                    // There's an actual error with the query or database.
                    if (!$findVideo->execute()) {
                        ladybug_dump($findVideo->errorInfo());
                        exit(1);
                    }

                    $oldVideo = $findVideo->fetch(PDO::FETCH_ASSOC);

                    $video = $this->videoRepo->findOneBy(array(
                        'property'=> $pId,
                    ));

                    $persist = false;
                    if (!$video) {
                        $persist = true;
                        $property = $this->propertyRepo->findOneById($pId);
                        $video = new $this->videoClass;
                        $video->setProperty($property);
                    }

                    if (!$video->getDescription()) {
                        $description = $this->descriptionRepo->findOneBy(array(
                            'property' => $pId,
                        ));
                        if ($description) {
                            $video->setDescription($description);
                        }
                    }

                    $status = $this->videoStatusRepo->findOneById((int) $oldVideo['status']);
                    $video->setStatus($status);

                    if ($oldVideo['title']) {
                        $video->setTitle($oldVideo['title']);
                    }

                    if ($oldVideo['description']) {
                        $video->setSummary($oldVideo['description']);
                    }

                    if ($oldVideo['duration']) {
                        $video->setDuration((int) $oldVideo['duration']);
                    }

                    if ($oldVideo['url']) {
                        $video->setCreateUrl($oldVideo['url']);
                    }

                    if ($oldVideo['vidyard_id']) {
                        $video->setVidyardId((int) $oldVideo['vidyard_id']);
                    }

                    if ($oldVideo['player_id']) {
                        $video->setPlayerId((int) $oldVideo['player_id']);
                    }

                    if ($oldVideo['vidyard_inline']) {
                        $video->setInline($oldVideo['vidyard_inline']);
                    }

                    if ($oldVideo['vidyard_iframe']) {
                        $video->setIframe($oldVideo['vidyard_iframe']);
                    }

                    if ($oldVideo['vidyard_light_box']) {
                        $video->setLightBox($oldVideo['vidyard_light_box']);
                    }

                    if ($oldVideo['submitted_date']) {
                        $video->setSubmitted(new \DateTime($oldVideo['submitted_date']));
                    }

                    if ($oldVideo['published_date']) {
                        $video->setPublished(new \DateTime($oldVideo['published_date']));
                    }

                    if ($oldVideo['submitted_by']) {
                        $user = $this->userRepo->findOneBy(array(
                            'email' => $oldVideo['submitted_by'],
                        ));
                        if ($user) {
                            $video->setSubmittedBy($user);
                        }
                    }

                    if ($oldVideo['published_by']) {
                        $user = $this->userRepo->findOneBy(array(
                            'email' => $oldVideo['published_by'],
                        ));
                        if ($user) {
                            $video->setPublishedBy($user);
                        }
                    }

                    if ($oldVideo['note_updated']) {
                        $video->setNoteUpdated(new \DateTime($oldVideo['note_updated']));
                    }

                    if ($oldVideo['notes']) {
                        $video->setNote($oldVideo['notes']);
                    }

                    if ($persist) {
                        $video->setActive(0);
                        $this->em->persist($video);
                    }

                    echo '+';
                    $this->em->flush();
                }
            }


            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);
    }

    /*
     * Activities
     */
    private function getActivities()
    {
        $min = 0;
        $max = 100;
        $currentId = $this->activityRepo->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $cFinder = $this->pdo->prepare(
            'SELECT COUNT(*) FROM activity_log
             WHERE id > :current'
        );
        $cFinder->bindvalue(':current', (int) $currentId, PDO::PARAM_INT);

        // There's an actual error with the query or database.
        if (!$cFinder->execute()) {
            ladybug_dump($cFinder->errorInfo());
            exit(1);
        }
        $count = $cFinder->fetchColumn();

        $result = $this->pdo->prepare(
            'SELECT * FROM activity_log
             WHERE id > :current
             LIMIT :max
             OFFSET :min'
        );

        do {
            $result->bindvalue(':current', (int) $currentId, PDO::PARAM_INT);
            $result->bindvalue(':max', (int) $max, PDO::PARAM_INT);
            $result->bindvalue(':min', (int) $min, PDO::PARAM_INT);

            // There's an actual error with the query or database.
            if (!$result->execute()) {
                ladybug_dump($result->errorInfo());
                exit(1);
            }

            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $activity = new $this->activityClass;
                if ($row['property_id']) {
                    $property = $this->propertyRepo->findOneBy(array(
                        'hash' => $row['property_id'],
                    ));
                    if ($property) {
                        $activity->setProperty($property);
                    }
                }
                $user = $this->userRepo->findOneBy(array(
                    'email' => $row['username'],
                ));
                if ($user) {
                    $activity->setActiveUser($user);
                }
                $activity->setAction($row['action']);
                $activity->setMadeChange((int) $row['made_change']);
                $activity->setCreatedAt(new \DateTime($row['created_at']));
                $this->em->persist($activity);
                $this->em->flush();
                echo '+';
            }


            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);
    }


    /*
     * Analytics
     */
    private function getAnalytics()
    {
        // Device Types
        $types = $this->deviceTypeList();
        foreach ($types as $dt) {
            $type = $this->deviceTypeRepo->findOneBy(array(
                'name'=> $dt,
            ));

            if (!$type) {
                $type = new $this->deviceTypeClass;
                $type->setName($dt);
                $this->em->persist($type);
                $this->em->flush();
                echo '+';
            }
        }

        // Analytics
        $min = 0;
        $max = 100;
        $currentDate = $this->analyticRepo->createQueryBuilder('a')
            ->select('a.reportDate')
            ->orderBy('a.reportDate', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        if (!$currentDate) {
            $currentDate = new \DateTime('2013-01-01 00:00:00');
            $currentDate = $currentDate->format('Y-m-d H:i:s');
        } else {
            $currentDate = $currentDate[0]['reportDate'];
            $currentDate = $currentDate->format('Y-m-d H:i:s');
        }

        $cFinder = $this->pdo->prepare(
            'SELECT COUNT(*) FROM analytics
             WHERE report_date >= :current'
        );
        $cFinder->bindvalue(':current', $currentDate);

        // There's an actual error with the query or database.
        if (!$cFinder->execute()) {
            ladybug_dump($cFinder->errorInfo());
            exit(1);
        }
        $count = $cFinder->fetchColumn();

        $result = $this->pdo->prepare(
            'SELECT * FROM analytics
             WHERE report_date >= :current
             ORDER BY report_date ASC
             LIMIT :max
             OFFSET :min'
        );

        do {
            $result->bindvalue(':current', $currentDate);
            $result->bindvalue(':max', (int) $max, PDO::PARAM_INT);
            $result->bindvalue(':min', (int) $min, PDO::PARAM_INT);

            // There's an actual error with the query or database.
            if (!$result->execute()) {
                ladybug_dump($result->errorInfo());
                exit(1);
            }

            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                if ($row['property_id']) {
                    $property = $this->propertyRepo->findOneBy(array(
                        'hash' => $row['property_id'],
                    ));
                    if (!$property) {
                        continue;
                    }
                } else {
                    continue;
                }

                if ($row['device_type']) {
                    $device = $this->deviceTypeRepo->findOneBy(array(
                        'name' => $row['device_type'],
                    ));
                } else {
                    continue;
                }

                $analytic = $this->analyticRepo->findOneBy(array(
                    'property' => $property,
                    'reportDate' => new \DateTime($row['report_date']),
                    'device' => $device,
                ));

                if (!$analytic) {
                    $analytic = new $this->analyticClass;
                    $analytic->setProperty($property);
                    $analytic->setDevice($device);
                    $analytic->setReportDate(new \DateTime($row['report_date']));
                    $analytic->setOnlineRateClicks((int) $row['online_rate_clicks']);
                    $analytic->setCouponViews((int) $row['coupon_views']);
                    $analytic->setFeaturedAdClicks((int) $row['featured_ad_clicks']);
                    $analytic->setDetailViews((int) $row['detail_views']);
                    $this->em->persist($analytic);
                    $this->em->flush();
                    echo '+';
                }
            }


            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);

    }

    /*
     * Confirmations
     */
    private function getConfirmations()
    {
        $min = 0;
        $max = 100;
        $cFinder = $this->pdo->prepare(
            'SELECT COUNT(*) FROM ax_confirmations'
        );

        // There's an actual error with the query or database.
        if (!$cFinder->execute()) {
            ladybug_dump($cFinder->errorInfo());
            exit(1);
        }
        $count = $cFinder->fetchColumn();

        $result = $this->pdo->prepare(
            'SELECT * FROM ax_confirmations
             LEFT JOIN ax_contracts
             ON ax_confirmations.contract_id = ax_contracts.id
             LEFT JOIN ax_orders
             ON ax_contracts.order_number_id = ax_orders.id
             LEFT JOIN ax_customers
             ON ax_orders.customer_id = ax_customers.id
             LIMIT :max
             OFFSET :min'
        );

        do {
            $result->bindvalue(':max', (int) $max, PDO::PARAM_INT);
            $result->bindvalue(':min', (int) $min, PDO::PARAM_INT);

            // There's an actual error with the query or database.
            if (!$result->execute()) {
                ladybug_dump($result->errorInfo());
                exit(1);
            }

            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                if ($row['customer_number']) {
                    $pId = $this->propertyRepo->createQueryBuilder('p')
                        ->select('p.id')
                        ->where('p.axNumber = :ax')
                        ->setParameter('ax', $row['customer_number'])
                        ->getQuery()
                        ->getResult();

                    $product = $this->productsRepo->createQueryBuilder('pr')
                        ->select('pr.id')
                        ->where('pr.code = :code')
                        ->setParameter('code', $row['item_code'])
                        ->getQuery()
                        ->getResult();

                    if (!$pId || !$product) {
                        continue;
                    }

                    $contract = $this->contractRepo->createQueryBuilder('c')
                        ->select('c.id')
                        ->where('c.property = :id')
                        ->andWhere('c.product = :prodId')
                        ->andWhere('c.orderNumber = :order')
                        ->andWhere('c.startDate = :start')
                        ->setParameter('id', $pId[0]['id'])
                        ->setParameter('prodId', $product[0]['id'])
                        ->setParameter('order', $row['order_number'])
                        ->setParameter('start', new \DateTime($row['start_date']))
                        ->getQuery()
                        ->getResult();

                    if (!$contract) {
                        continue;
                    }

                    $contractId = $contract[0]['id'];
                    $confirmation = $this->confirmationRepo->findOneBy(array(
                        'contract' => $contractId,
                        'confirmedIssue' => (int) $row['confirmed'],
                    ));

                    if (!$confirmation) {
                        $contract = $this->contractRepo->findOneById($contractId);
                        $confirmation = new $this->confirmationClass;
                        $confirmation->setContract($contract);
                        $confirmation->setConfirmedIssue((int) $row['confirmed']);

                        if ($row['updated_by']) {
                            $user = $this->userRepo->findOneBy(array(
                                'email' => $row['updated_by'],
                            ));
                            if ($user) {
                                $confirmation->setConfirmedBy($user);
                            }
                        }
                        echo '+';
                        $this->em->persist($confirmation);
                        $this->em->flush();
                    }
                } else {
                    continue;
                }
            }

            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);
    }

    /*
     * Call Logs
     */
    private function getCalls()
    {
        $min = 0;
        $max = 100;
        $count = $this->propertyRepo->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $result = $this->pdo->prepare(
            'SELECT * FROM call_logs
             WHERE property_id = :hash
             ORDER BY start_time ASC
             LIMIT :max
             OFFSET :min'
        );

        $cFinder = $this->pdo->prepare(
            'SELECT COUNT(*) FROM call_logs
            WHERE property_id = :hash'
        );




        do {
            $properties = $this->propertyRepo->createQueryBuilder('p')
                ->select('p.id, p.hash')
                ->setFirstResult($min)
                ->setMaxResults($max)
                ->getQuery()
                ->getResult();

            foreach ($properties as $p) {
                $pId = $p['id'];
                $pHash = $p['hash'];

                // Find Call
                $cFinder->bindvalue(':hash', $pHash);

                // There's an actual error with the query or database.
                if (!$cFinder->execute()) {
                    ladybug_dump($cFinder->errorInfo());
                    exit(1);
                }
                $callCount = $cFinder->fetchColumn();

                if ((int) $count == 0) {
                    continue;
                }

                $callMin = 0;
                $callMax = 100;
                do {
                    $result->bindvalue(':hash', $pHash);
                    $result->bindvalue(':max', (int) $callMax, PDO::PARAM_INT);
                    $result->bindvalue(':min', (int) $callMin, PDO::PARAM_INT);

                    // There's an actual error with the query or database.
                    if (!$result->execute()) {
                        ladybug_dump($result->errorInfo());
                        exit(1);
                    }

                    $batch = 0;
                    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($rows as $row) {
                        $call = $this->callLogRepo->findOneBy(array(
                            'callId' => $row['call_id'],
                        ));

                        if (!$call) {
                            $property = $this->propertyRepo->findOneById($pId);
                            $call = new $this->callLogClass;
                            $call->setProperty($property);
                            $call->setCallId($row['call_id']);
                            $call->setStartTime(new \DateTime($row['start_time']));
                            $call->setDuration((int) $row['duration']);
                            $call->setTalkTime((int) $row['talk_time']);
                            $call->setCallNum((int) $row['caller_num']);
                            $call->setTrackingNum((int) $row['tracking_num']);
                            if ($row['endpoint_num']) {
                                $call->setEndpointNum((int) $row['endpoint_num']);
                            }

                            if ($row['name']) {
                                $call->setLocation($row['name']);
                            }

                            if ($row['state']) {
                                $state = $this->stateRepo->findOneBy(array(
                                    'abbreviation' => $row['state'],
                                ));
                                if ($state) {
                                    $call->setState($state);
                                }
                            }

                            if ($row['zip']) {
                                $pc = $this->postalCodeRepo->findOneBy(array(
                                    'code' => $row['zip'],
                                ));
                                if ($pc) {
                                    $call->setPostalCode($pc);
                                }
                            }

                            $call->setAccount((int) $row['property_note']);
                            $call->setCampaign($row['campaign']);
                            if ($row['recording_url']) {
                                $call->setRecordingUrl($row['recording_url']);
                            }

                            $this->em->persist($call);
                            $batch++;
                            echo '+';
                            if ($batch == 20) {
                                $batch = 0;
                            }
                        }
                    }
                    $this->em->flush();

                    $callMin = ($callMin + $callMax);
                    ladybug_dump('Calls: '.$callMin.' of '.$callCount."\n");
                } while ($callMin <= $callCount);
            }

            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);
    }




    /*
     * Postal Codes
     */
    private function getPostalCodes()
    {
        $min = 0;
        $max = 100;

        $cFinder = $this->pdo->prepare(
            'SELECT COUNT(*) FROM zip_codes'
        );
        // There's an actual error with the query or database.
        if (!$cFinder->execute()) {
            ladybug_dump($cFinder->errorInfo());
            exit(1);
        }
        $count = $cFinder->fetchColumn();

        $result = $this->pdo->prepare(
            'SELECT * FROM zip_codes
             LIMIT :max
             OFFSET :min'
        );

        do {
            $result->bindvalue(':max', (int) $max, PDO::PARAM_INT);
            $result->bindvalue(':min', (int) $min, PDO::PARAM_INT);

            // There's an actual error with the query or database.
            if (!$result->execute()) {
                ladybug_dump($result->errorInfo());
                exit(1);
            }

            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $pCode = $this->postalCodeRepo->findOneBy(array(
                    'code' => $row['zip']
                ));
                $hasCityCenter = true;
                $city = null;
                $state = null;
                $country = null;

                if ($row['city']) {
                    $city = $row['city'];
                } else {
                    $hasCityCenter = false;
                }

                if ($row['state'] && $hasCityCenter) {
                    $state = $this->stateRepo->findOneBy(array(
                        'abbreviation' => $row['state']
                    ));
                } else {
                    $hasCityCenter = false;
                }

                if ($row['country'] && $hasCityCenter) {
                    $country = $this->countryRepo->findOneBy(array(
                        'code' => $row['country']
                    ));
                } else {
                    $hasCityCenter = false;
                }

                if (!$pCode) {
                    $pCode = new $this->postalCodeClass;
                    $pCode->setCode($row['zip']);
                    $this->em->persist($pCode);
                    $this->em->flush();
                    echo '+';
                }

                if ($hasCityCenter && $state && $country) {
                    $cc = $this->cityCenterRepo->findBy(array(
                        'city' => $city,
                        'state' => $state,
                        'country' => $country,
                    ));

                    if ($cc) {
                        foreach ($cc as $c) {
                            if (!$pCode->hasCity($c)) {
                                $pCode->addCity($c);
                                $this->em->flush();
                                echo '.';
                            }
                        }
                    }
                }
            }


            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);
    }

    /*
     * City Centers
     */
    private function getCityCenters()
    {
        $min = 0;
        $max = 100;

        $cFinder = $this->pdo->prepare(
            'SELECT COUNT(*) FROM city_centers
            WHERE latitude IS NOT NULL
            AND longitude IS NOT NULL'
        );
        // There's an actual error with the query or database.
        if (!$cFinder->execute()) {
            ladybug_dump($cFinder->errorInfo());
            exit(1);
        }
        $count = $cFinder->fetchColumn();

        $result = $this->pdo->prepare(
            'SELECT * FROM city_centers
             WHERE latitude IS NOT NULL
             AND longitude IS NOT NULL
             LIMIT :max
             OFFSET :min'
        );

        do {
            $result->bindvalue(':max', (int) $max, PDO::PARAM_INT);
            $result->bindvalue(':min', (int) $min, PDO::PARAM_INT);

            // There's an actual error with the query or database.
            if (!$result->execute()) {
                ladybug_dump($result->errorInfo());
                exit(1);
            }

            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $hasCityCenter = true;
                $city = null;
                $state = null;
                $country = null;

                if ($row['city']) {
                    $city = $row['city'];
                } else {
                    $hasCityCenter = false;
                }

                if ($row['state'] && $hasCityCenter) {
                    $state = $this->stateRepo->findOneBy(array(
                        'abbreviation' => $row['state']
                    ));
                } else {
                    $hasCityCenter = false;
                }

                if ($row['country'] && $hasCityCenter) {
                    $country = $this->countryRepo->findOneBy(array(
                        'code' => $row['country']
                    ));
                } else {
                    $hasCityCenter = false;
                }
                if ($hasCityCenter && $city && $state && $country) {
                    $cc = $this->cityCenterRepo->findOneBy(array(
                        'city' => $city,
                        'state' => $state,
                        'country' => $country,
                    ));

                    if (!$cc) {
                        $cc = new $this->cityCenterClass;
                        $cc->setCity($city);
                        $cc->setState($state);
                        $cc->setCountry($country);
                        $cc->setLatitude($row['latitude']);
                        $cc->setLongitude($row['longitude']);

                        if ($row['hero_image_url']) {
                            $cc->setHeroImage($row['hero_image_url']);
                        }
                        $this->em->persist($cc);
                        $this->em->flush();
                        echo '+';
                    }
                }
            }


            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);
    }

    /*
     * IHG
     */
    private function getIhg()
    {
        $min = 0;
        $max = 100;
        $cFinder = $this->pdo->prepare(
            'SELECT COUNT(*) FROM properties
             WHERE ihg_property_id IS NOT NULL'
        );

        // There's an actual error with the query or database.
        if (!$cFinder->execute()) {
            ladybug_dump($cFinder->errorInfo());
            exit(1);
        }
        $count = $cFinder->fetchColumn();

        $result = $this->pdo->prepare(
            'SELECT id, ihg_property_id FROM properties
             WHERE ihg_property_id IS NOT NULL
             LIMIT :max
             OFFSET :min'
        );

        $ihgFinder = $this->pdo->prepare(
            'SELECT * FROM ihg_properties
             WHERE hotel_code = :id'
        );

        $aFinder = $this->pdo->prepare(
            'SELECT * FROM addresses
             WHERE id = :id'
        );


        do {
            $result->bindvalue(':max', (int) $max, PDO::PARAM_INT);
            $result->bindvalue(':min', (int) $min, PDO::PARAM_INT);

            // There's an actual error with the query or database.
            if (!$result->execute()) {
                ladybug_dump($result->errorInfo());
                exit(1);
            }

            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $hotelCode = $row['ihg_property_id'];
                $hash = $row['id'];

                $ihgFinder->bindvalue(':id', $hotelCode);

                // There's an actual error with the query or database.
                if (!$ihgFinder->execute()) {
                    ladybug_dump($ihgFinder->errorInfo());
                    exit(1);
                }

                $oldIhg = $ihgFinder->fetch(PDO::FETCH_ASSOC);

                if (!$oldIhg) {
                    continue;
                }

                $property = $this->propertyRepo->findOneBy(array(
                    'hash' => $hash
                ));

                $ihg = $this->ihgRepo->findOneBy(array(
                    'hotelCode' => $row['ihg_property_id'],
                ));

                if (!$ihg) {
                    $ihg = new $this->ihgClass;
                    $ihg->setHotelCode($oldIhg['hotel_code']);

                    // Get Address
                    $aFinder->bindvalue(':id', (int) $oldIhg['address_id'], PDO::PARAM_INT);

                    // There's an actual error with the query or database.
                    if (!$aFinder->execute()) {
                        ladybug_dump($aFinder->errorInfo());
                        exit(1);
                    }

                    $oldAddress = $aFinder->fetch(PDO::FETCH_ASSOC);
                    $zip = null;
                    $zip = $this->postalCodeRepo->findOneBy(array(
                        'code' => $oldAddress['zip']
                    ));
                    $country = null;
                    $country = $this->countryRepo->findOneBy(array(
                        'code' => $oldAddress['country']
                    ));
                    $state = null;
                    $state = $this->stateRepo->findOneBy(array(
                        'abbreviation' => $oldAddress['state'],
                    ));

                    $newAdd = $this->addressRepo->findOneBy(array(
                        'postalCode' => $zip,
                        'country' => $country,
                        'state' => $state,
                        'city' => $oldAddress['city'],
                        'line1' => $oldAddress['line1'],
                    ));

                    if (!$newAdd) {
                        if ($state && $zip && $country) {
                            $newAdd = new $this->addressClass;
                            $newAdd->setLine1($oldAddress['line1']);
                            $newAdd->setLine2($oldAddress['line2']);
                            $newAdd->setCity($oldAddress['city']);
                            $newAdd->setState($state);
                            $newAdd->setPostalCode($zip);
                            $newAdd->setCountry($country);

                            $hash = $newAdd->generateHash();

                            $hasHash = $this->addressRepo->findOneBy(array(
                                'hash' => $hash,
                            ));

                            if ($hasHash) {
                                $ihg->setAddress($hasHash);
                            } else {
                                $newAdd->setLatitude($oldAddress['latitude']);
                                $newAdd->setLongitude($oldAddress['longitude']);
                                $this->em->persist($newAdd);
                                $ihg->setAddress($newAdd);
                            }
                        } else {
                            continue;
                        }
                    } else {
                        $ihg->setAddress($newAdd);
                    }

                    $ihg->setName($oldIhg['name']);
                    $ihg->setDescription($oldIhg['description']);
                    $ihg->setRate($oldIhg['rate']);

                    if ($oldIhg['brand']) {
                        $brand = $this->brandRepo->findOneBy(array(
                            'code' => $oldIhg['brand'],
                        ));

                        if (!$brand) {
                            $brand = new $this->brandClass;
                            $brand->setCode($oldIhg['brand']);
                        }
                        $ihg->setBrand($brand);
                    }

                    $ihg->setRateType($oldIhg['rate_type']);
                    $ihg->setImage($oldIhg['image']);
                    $ihg->setPhone($oldIhg['phone']);
                    $ihg->setUrl($oldIhg['url']);
                    $ihg->setExpires(new \DateTime($oldIhg['end_date']));
                    $this->em->persist($ihg);
                    echo '+';
                }

                if ($ihg->getProperty() != $property) {
                    $ihg->setProperty($property);
                    $this->em->flush();
                    echo '.';
                }
            }


            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);

        $this->em->flush();
        $this->em->clear();
    }

    /*
     * Rates
     */
    private function getRates()
    {
        // Rate Types
        $types = $this->rateTypeList();
        foreach ($types as $pt) {
            $type = $this->rateTypeRepo->findOneBy(array(
                'name'=> $pt,
            ));

            if (!$type) {
                $type = new $this->rateTypeClass;
                $type->setName($pt);
                $this->em->persist($type);
                $this->em->flush();
                echo '+';
            }
        }

        $min = 0;
        $max = 100;
        $count = $this->propertyRepo->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $result = $this->pdo->prepare(
            'SELECT * FROM property_advertisements
             WHERE property_id = :hash
             ORDER BY start_date ASC'
        );

        do {
            $properties = $this->propertyRepo->createQueryBuilder('p')
                ->select('p.id, p.hash')
                ->setFirstResult($min)
                ->setMaxResults($max)
                ->getQuery()
                ->getResult();

            foreach ($properties as $p) {
                $pId = $p['id'];
                $pHash = $p['hash'];

                $result->bindvalue(':hash', $pHash);

                // There's an actual error with the query or database.
                if (!$result->execute()) {
                    ladybug_dump($result->errorInfo());
                    exit(1);
                }

                $rows = $result->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $type = $this->rateTypeRepo->findOneBy(array(
                        'name' => $row['rate_type'],
                    ));

                    $adType = $this->productTypesRepo->findOneBy(array(
                        'type' => $row['advertising_type'],
                    ));

                    $rate = $this->rateRepo->findOneBy(array(
                        'property' => $p['id'],
                        'startDate' => new \DateTime($row['start_date']),
                        'endDate' => new \DateTime($row['end_date']),
                        'type' => $type,
                        'advertisementType' => $adType,
                        'ratePretty' => $row['rate_pretty'],
                        'rateValue' => $row['rate_value']
                    ));

                    if (!$rate) {
                        $property = $this->propertyRepo->findOneById($pId);

                        $rate = new $this->rateClass;
                        $rate->setProperty($property);
                        $rate->setType($type);
                        $rate->setAdvertisementType($adType);
                        $rate->setStartDate(new \DateTime($row['start_date']));
                        $rate->setEndDate(new \DateTime($row['end_date']));
                        if ($row['restrictions']) {
                            $rate->setRestrictions($row['restrictions']);
                        }
                        if ($row['is_approved']) {
                            $rate->setApproved($row['is_approved']);
                        }
                        $rate->setRateValue((int) $row['rate_value']);

                        $this->em->persist($rate);
                        echo '+';
                        $this->em->flush();
                    }

                    if ($row['created_by']) {
                        $user = $this->userRepo->findOneBy(array(
                            'email' => $row['created_by'],
                        ));
                        if ($user) {
                            $rate->setUpdatedBy($user);
                            $this->em->flush();
                        }
                    }

                    if ($row['updated_by']) {
                        $user = $this->userRepo->findOneBy(array(
                            'email' => $row['updated_by'],
                        ));
                        if ($user) {
                            $rate->setUpdatedBy($user);
                            $this->em->flush();
                        }
                    }
                }
            }

            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);

    }



    /*
     * Photos
     */
    private function getPhotos()
    {
        $min = 0;
        $max = 100;
        $count = $this->propertyRepo->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $result = $this->pdo->prepare(
            'SELECT * FROM property_photos
             WHERE property_id = :hash'
        );

        do {
            $properties = $this->propertyRepo->createQueryBuilder('p')
                ->select('p.id, p.hash')
                ->setFirstResult($min)
                ->setMaxResults($max)
                ->getQuery()
                ->getResult();

            foreach ($properties as $p) {
                $pId = $p['id'];
                $pHash = $p['hash'];

                $result->bindvalue(':hash', $pHash);

                // There's an actual error with the query or database.
                if (!$result->execute()) {
                    ladybug_dump($result->errorInfo());
                    exit(1);
                }

                $rows = $result->fetchAll(PDO::FETCH_ASSOC);
                $addPhotos = [];
                $hasPhotos = [];
                foreach ($rows as $row) {
                    $hasPhoto = $this->photoRepo->findOneBy(array(
                        'property' => $pId,
                        'original' => $row['url_original'],
                    ));
                    if ($hasPhoto) {
                        array_push($hasPhotos, $hasPhoto->getId());
                    } else {
                        $addPhotos[] = array(
                            'original' => $row['url_original'],
                            'extra_large' => $row['url_extra_large'],
                            'large' => $row['url_large'],
                            'medium' => $row['url_medium'],
                            'small' => $row['url_small'],
                            'thumbnail' => $row['url_extra_small']
                        );
                    }
                }

                $propPhotos = $this->photoRepo->findBy(array(
                    'property' => $pId,
                ));

                foreach ($propPhotos as $p) {
                    if (!in_array($p->getId(), $hasPhotos)) {
                        $this->em->remove($p);
                        $this->em->flush();
                        echo '-';
                    }
                }

                foreach ($addPhotos as $a) {
                    $property = $this->propertyRepo->findOneById($pId);
                    $new = new $this->photoClass;
                    $new->setProperty($property);
                    $new->setOriginal($a['original']);
                    $new->setExtraLarge($a['extra_large']);
                    $new->setLarge($a['large']);
                    $new->setMedium($a['medium']);
                    $new->setSmall($a['small']);
                    $new->setThumbnail($a['thumbnail']);
                    $this->em->persist($new);
                    $this->em->flush();
                    echo '+';
                }
            }

            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);
    }


    /*
     * Social
     */
    private function getSocial()
    {
        // Social Data Types
        $types = $this->socialDataTypeList();
        foreach ($types as $s) {
            $type = $this->socialDataTypeRepo->findOneBy(array(
                'type'=> $s['type'],
            ));

            if (!$type) {
                $type = new $this->socialDataTypeClass;
                $type->setType($s['type']);
                $type->setName($s['name']);
                $this->em->persist($type);
                $this->em->flush();
                echo '+';
            }
        }

        // Social
        $min = 0;
        $max = 100;
        $count = $this->propertyRepo->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $findSocial = $this->pdo->prepare(
            'SELECT * FROM properties
            WHERE id = :id
            AND social_active = :val'
        );

        do {
            $properties = $this->propertyRepo->createQueryBuilder('p')
                ->select('p.id, p.hash')
                ->setFirstResult($min)
                ->setMaxResults($max)
                ->getQuery()
                ->getResult();

            foreach ($properties as $p) {
                $pId = $p['id'];
                $pHash = $p['hash'];

                // Find Url
                $findSocial->bindvalue(':id', $pHash);
                $findSocial->bindvalue(':val', 1, PDO::PARAM_INT);

                // There's an actual error with the query or database.
                if (!$findSocial->execute()) {
                    ladybug_dump($findSocial->errorInfo());
                    exit(1);
                }

                $oldSocial = $findSocial->fetch(PDO::FETCH_ASSOC);
                if (!$oldSocial) {
                    continue;
                }

                $social = $this->socialRepo->findOneBy(array(
                    'property' => $pId,
                ));

                if ($social) {
                    if ($oldSocial['social_link']) {
                        $social->setUrl($oldSocial['social_link']);
                    }
                    $social->setActive(1);
                    $this->em->flush();
                    echo '.';
                    continue;
                }

                $social = new $this->socialClass;
                $property = $this->propertyRepo->findOneById($pId);
                $social->setProperty($property);
                $social->setActive(1);
                if ($oldSocial['social_link']) {
                    $social->setUrl($oldSocial['social_link']);
                }
                $this->em->persist($social);
                $this->em->flush();
                echo '+';
            }

            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);

        // Social Data
        $this->em->clear();
        $min = 0;
        $max = 100;
        $count = $this->socialRepo->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $findData = $this->pdo->prepare(
            'SELECT * FROM social_data
            WHERE account_number = :aNum'
        );

        do {
            $socials = $this->socialRepo->createQueryBuilder('s')
                ->select('p.id as id, p.hash, p.axNumber, s.id as social')
                ->join('s.property', 'p')
                ->setFirstResult($min)
                ->setMaxResults($max)
                ->getQuery()
                ->getResult();

            foreach ($socials as $s) {
                $pId = $s['id'];
                $pHash = $s['hash'];
                $aNum = $s['axNumber'];
                $sId = $s['social'];


                $findData->bindvalue(':aNum', $aNum);
                // There's an actual error with the query or database.
                if (!$findData->execute()) {
                    ladybug_dump($findData->errorInfo());
                    exit(1);
                }

                $rows = $findData->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $type = $this->socialDataTypeRepo->findOneBy(array(
                        'type' => $row['network'],
                    ));

                    if (!$type) {
                        ladybug_dump($row['network']);
                        exit();
                    }

                    $data = $this->socialDataRepo->findOneBy(array(
                        'social' => $sId,
                        'yrmo' => (int) $row['yrmo'],
                        'type' => $type
                    ));

                    $new = false;
                    if (!$data) {
                        $social = $this->socialRepo->findOneById($sId);
                        $data = new $this->socialDataClass;
                        $data->setSocial($social);
                        $data->setType($type);
                        $data->setYrmo((int) $row['yrmo']);
                        $new = true;
                    }
                    $data->setFans((int) $row['fans']);

                    if ($new) {
                        $this->em->persist($data);
                        echo '+';
                    }
                    $this->em->flush();
                    echo '.';
                }
            }

            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);
    }


    private function getTollFrees()
    {
        // Add Toll Free if property has ever had one
        $min = 0;
        $max = 100;
        $count = $this->propertyRepo->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $result = $this->pdo->prepare(
            'SELECT toll_free_phone FROM properties
             WHERE id = :hash
             AND toll_free_phone IS NOT NULL'
        );


        do {
            $properties = $this->propertyRepo->createQueryBuilder('p')
                ->select('p.id, p.hash')
                ->setFirstResult($min)
                ->setMaxResults($max)
                ->getQuery()
                ->getResult();

            foreach ($properties as $p) {
                $pId = $p['id'];
                $pHash = $p['hash'];

                $contracts = $this->contractRepo->createQueryBuilder('c')
                    ->select('p.id, p.typeDescription')
                    ->join('c.product', 'p')
                    ->where('c.property = :prop')
                    ->andWhere('p.type = :type')
                    ->setParameter('prop', $pId)
                    ->setParameter('type', 3)
                    ->getQuery()
                    ->getResult();

                if (!$contracts) {
                    continue;
                }

                foreach ($contracts as $c) {
                    $type = $c['typeDescription'];
                    $tfType = null;
                    if ($type == 'Other Mktg Tools') {
                        $tfType = 2;
                    } elseif ($type == 'Print Mktg Tools' || $type == 'Print Advertising') {
                        $tfType = 1;
                    }

                    if ($tfType) {
                        $hasTf = $this->tollFreeRepo->findOneBy(array(
                            'property' => $pId,
                            'type' => $tfType
                        ));
                        if (!$hasTf) {
                            $property = $this->propertyRepo->findOneById($pId);
                            $pType = $this->productTypesRepo->findOneById($tfType);
                            $tf = new $this->tollFreeClass;
                            $tf->setProperty($property);
                            $tf->setType($pType);
                            $tf->setActive(0);
                            $this->em->persist($tf);
                            $this->em->flush();
                            echo '+';
                        }
                    } else {
                        ladybug_dump($c['typeDescription']);
                        ladybug_dump($c['id']);
                        exit();
                    }
                }

                $result->bindvalue(':hash', $pHash);
                // There's an actual error with the query or database.
                if (!$result->execute()) {
                    ladybug_dump($result->errorInfo());
                    exit(1);
                }

                $oldTF = $result->fetch(PDO::FETCH_ASSOC);

                if (!$oldTF) {
                    continue;
                }

                $tf = $this->tollFreeRepo->findOneBy(array(
                    'property' => $pId,
                    'type' => 2,
                ));

                if ($tf) {
                    if ($tf->getNumber() != $oldTF['toll_free_phone']) {
                        $tf->setNumber($oldTF['toll_free_phone']);
                        $this->em->flush();
                        echo '.';
                    }
                }
            }

            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);
    }

    /* ============================ */
    /* ------ Setup on Prod ------- */
    /* ============================ */

    /*
     * Sales Reps
     */
    private function getSalesReps()
    {
        $result = $this->pdo->query("SELECT * FROM sales_reps");
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $rep = $this->repRepo->findOneBy(array(
                'name' => $row['name'],
                'code' => $row['code']
            ));

            if (!$rep) {
                $rep = new $this->repClass;
            }
            $rep->setName($row['name']);
            $rep->setCode($row['code']);
            $this->em->persist($rep);
            $this->em->flush();
            echo '+';
        }
    }

    /*
     * States
     */
    private function importStates()
    {
        $states = $this->statesList();
        foreach ($states as $key => $name) {
            $state = $this->stateRepo->findOneBy(array(
                'name' => $name,
                'abbreviation' => $key
            ));

            if (!$state) {
                $state = new $this->stateClass;
            }
            $state->setName($name);
            $state->setAbbreviation($key);
            $state->setSlug(
                $this->container->get('app.utils.slugger')->slugify($name)
            );
            $this->em->persist($state);
            $this->em->flush();
            echo '+';
        }
    }

    /*
     * Books
     */
    private function getBooks()
    {
        $result = $this->pdo->query("SELECT * FROM ax_books");
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $book = $this->bookRepo->findOneBy(array(
                'name'=> $row['name'],
                'code'=> $row['code'],
            ));

            if (!$book) {
                $book = new $this->bookClass;
            }
            $book->setName($row['name']);
            $book->setCode($row['code']);
            $this->em->persist($book);
            $this->em->flush();
            echo '+';
        }

        $books = $this->bookList();
        foreach ($books as $key => $val) {
            $book = $this->bookRepo->findOneBy(array(
                'code'=> $key,
            ));

            if ($book) {
                $book->setDisplayName($val['display']);
                $book->setNewsletterName($val['newsletter']);
                $this->em->flush();
                echo '+';
            }
        }
    }

    /*
     * Travel Types
     */
    private function getTravelTypes()
    {
        $result = $this->pdo->query("SELECT * FROM travel_types");
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $type = $this->typesRepo->findOneBy(array(
                'name' => $row['value'],
            ));

            if (!$type) {
                $type = new $this->typesClass;
            }
            $type->setName($row['value']);
            $type->setSlug($row['value']);
            $this->em->persist($type);
            $this->em->flush();
            echo '+';
        }
    }

    /*
     * Special Types
     */
    private function getSpecialTypes()
    {
        // Special Types
        $types = $this->specialTypeList();
        foreach ($types as $pt) {
            $type = $this->specialTypeRepo->findOneBy(array(
                'type'=> $pt,
            ));

            if (!$type) {
                $type = new $this->specialTypeClass;
                $type->setType($pt);
                $this->em->persist($type);
                $this->em->flush();
                echo '+';
            }
        }
    }

    /*
     * Countries
     */
    private function getCountries()
    {
        $result = $this->pdo->query("SELECT * FROM country");
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $country = $this->countryRepo->findOneBy(array(
                'code' => $row['code']
            ));

            if (!$country) {
                $country = new $this->countryClass;
            }
            $country->setName($row['name']);
            $country->setCode($row['code']);
            $this->em->persist($country);
            $this->em->flush();
            echo '+';
        }
    }

    /*
     * Amenities List
     */
    private function setupAmenities()
    {
        $amenitiesList = [];
        $result = $this->pdo->query("SELECT * FROM amenities");
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $amenity = $this->amenitiesRepo->findOneBy(array(
                'name'=> $row['name'],
            ));

            if (!$amenity) {
                $amenity = new $this->amenitiesClass;
            }
            $amenity->setName($row['name']);
            $amenity->setSlug($row['name']);
            $amenity->setKeySelector($row['key_selector']);
            $this->em->persist($amenity);
            $this->em->flush();
            echo '+';

            $amenitiesList[$row['id']] = $row['key_selector'];
        }
    }

    /*
     * Products
     */
    private function getProducts()
    {
        // Product Types
        $types = $this->productTypesList();
        foreach ($types as $pt) {
            $type = $this->productTypesRepo->findOneBy(array(
                'type'=> $pt,
            ));

            if (!$type) {
                $type = new $this->productTypesClass;
                $type->setType($pt);
                $this->em->persist($type);
                $this->em->flush();
                echo '+';
            }
        }

        $result = $this->pdo->query(
            "SELECT DISTINCT(item_code), item_type, category, ad_size, description FROM ax_contracts"
        );
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $product = $this->productsRepo->findOneBy(array(
                'code'=> $row['item_code'],
            ));
            $flush = false;

            if (!$product) {
                $product = new $this->productsClass;
                $type = $this->productTypesRepo->findOneBy(array(
                    'type'=> $row['category'],
                ));
                $product->setType($type);
                $product->setCode($row['item_code']);
                $this->em->persist($product);
                $flush = true;
            }
            if ($row['item_type']) {
                if ($row['item_type'] != $product->getTypeDescription()) {
                    $product->setTypeDescription($row['item_type']);
                    $flush = true;
                }
            }
            if ($row['ad_size']) {
                if ($row['ad_size'] != $product->getAdSize()) {
                    $product->setAdSize($row['ad_size']);
                    $flush = true;
                }
            }
            if ($row['description']) {
                if ($row['description'] != $product->getDescription()) {
                    $product->setDescription($row['description']);
                    $flush = true;
                }
            }
            if ($flush) {
                $this->em->flush();
                echo '+';
            }
        }
    }

    /*
     * Properties
     */
    private function getProperties()
    {
        $min = 0;
        $max = 100;
        $cFinder = $this->pdo->prepare(
            'SELECT COUNT(*) FROM properties
             WHERE ax_account_number IS NOT NULL'
        );

        // There's an actual error with the query or database.
        if (!$cFinder->execute()) {
            ladybug_dump($cFinder->errorInfo());
            exit(1);
        }
        $count = $cFinder->fetchColumn();

        $result = $this->pdo->prepare(
            'SELECT * FROM properties
             WHERE ax_account_number IS NOT NULL
             LIMIT :max
             OFFSET :min'
        );

        do {
            $result->bindvalue(':max', (int) $max, PDO::PARAM_INT);
            $result->bindvalue(':min', (int) $min, PDO::PARAM_INT);

            // There's an actual error with the query or database.
            if (!$result->execute()) {
                ladybug_dump($result->errorInfo());
                exit(1);
            }

            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $address = null;
                $property = $this->propertyRepo->findOneBy(array(
                    'hash'=> $row['id'],
                ));

                if (!$property) {
                    continue;
                }

                if ($row['email']) {
                    $property->setEmail($row['email']);
                }
                if ($row['phone']) {
                    if ($row['phone'] != '(999) 999-9999') {
                        $property->setPhone($row['phone']);
                    }
                }
                if ($row['featured_amenities']) {
                    if (@unserialize($row['featured_amenities']) !== false) {
                        $data = unserialize($row['featured_amenities']);
                    } else {
                        $data = array();
                    }
                    $property->setFeaturedAmenities($data);
                }

                if ($row['is_lock'] == '1') {
                    $property->setRateLock(1);
                } else {
                    $property->setRateLock(0);
                }

                if ($row['show_online']) {
                    if ($row['show_online'] == '1') {
                        $property->setForceLive(1);
                    } else {
                        $property->setForceLive(0);
                    }
                } else {
                    $property->setForceLive(0);
                }

                $this->em->flush();

                $findDesc = $this->pdo->prepare(
                    'SELECT interstate_exit,
                        display_interstate,
                        display_exit
                    FROM property_descriptions
                    WHERE property_id = :id'
                );
                $findDesc->bindvalue(':id', (int) $row['id'], PDO::PARAM_INT);

                // There's an actual error with the query or database.
                if (!$findDesc->execute()) {
                    ladybug_dump($findDesc->errorInfo());
                    exit(1);
                }

                $desc = $findDesc->fetch(PDO::FETCH_ASSOC);

                if ($desc) {
                    $address = $property->getAddress();
                    if ($address) {
                        if ($desc['display_interstate'] && $desc['display_exit']) {
                            $display = $desc['display_interstate'].' '.$desc['display_exit'];
                            $address->setDisplayInterstateExit($display);
                        } elseif ($desc['display_interstate']) {
                            $address->setDisplayInterstateExit($desc['display_interstate']);
                        } elseif ($desc['display_exit']) {
                            $address->setDisplayInterstateExit($desc['display_exit']);
                        }
                    }
                }
                $this->em->flush();
                echo '+';
            }


            $min = ($min + $max);
            ladybug_dump($min.' of '.$count."\n");
        } while ($min <= $count);

        $this->em->flush();
        $this->em->clear();
    }





    /* ============================ */
    /* ---- Helper Functions ------ */
    /* ============================ */

    /*
     * Guide Book List
     */
    private function bookList()
    {
        $books = [];
        $books['NCC'] = array(
            'display' => 'Carolinas (NC, SC)',
            'newsletter' => 'Carolinas Digital Guide',
        );
        $books['FLB'] = array(
            'display' => 'Florida (FL)',
            'newsletter' => 'Florida Digital Guide',
        );
        $books['GAE'] = array(
            'display' => 'Georgia (GA)',
            'newsletter' => 'Georgia Digital Guide',
        );
        $books['MAM'] = array(
            'display' => 'Mid-America (MI, IN, KY, IL, MO, OH, IA, MN, WI)',
            'newsletter' => 'Mid America Digital Guide',
        );
        $books['VAC'] = array(
            'display' => 'Mid-Atlantic (DE, DC, MD, VA, WV)',
            'newsletter' => 'Mid Atlantic Digital Guide',
        );
        $books['PNB'] = array(
            'display' => 'Northeast (CT, MA, ME, NJ, NY, PA, RI, VT)',
            'newsletter' => 'Northeast Digital Guide',
        );
        $books['NWB'] = array(
            'display' => 'Northwest (OR, WA, MT, UT, WY, ID, ND, SD, NE)',
            'newsletter' => 'Northwest Digital Guide',
        );
        $books['TXB'] = array(
            'display' => 'South Central (AR, KS, OK, TX)',
            'newsletter' => 'South Central Digital Guide',
        );
        $books['CAD'] = array(
            'display' => 'Southwest (CA, NV)',
            'newsletter' => 'Southwest Digital Guide',
        );
        $books['CWC'] = array(
            'display' => 'Colorado (CO)',
            'newsletter' => 'Colorado Digital Guide',
        );
        $books['LAC'] = array(
            'display' => 'Louisiana/Mississippi (LA, MS)',
            'newsletter' => 'Louisiana/Mississippi Digital Guide',
        );
        $books['AWC'] = array(
            'display' => 'Arkansas (AR)',
            'newsletter' => 'Arkansas Digital Guide',
        );
        return $books;
    }

    /*
     * States List
     */
    private function statesList()
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

            // non-states
            'AA' => 'U.S. Armed Forces - Americas',
            'AE' => 'U.S. Armed Forces - Europe',
            'AP' => 'U.S. Armed Forces - Pacific',
            'AS' => 'American Samoa',
            'FM' => 'Federated States of Micronesia',
            'GU' => 'Guam',
            'MH' => 'Marshall Islands',
            'MP' => 'Northern Mariana Islands',
            'PW' => 'Palau',
            'PR' => 'Puerto Rico',
            'VI' => 'Virgin Islands',

            //Provinces of Canada
            'AB' => 'Alberta',
            'BC' => 'British Columbia',
            'MB' => 'Manitoba',
            'NB' => 'New Brunswick',
            'NL' => 'Newfoundland and Labrador',
            'NS' => 'Nova Scotia',
            'NT' => 'Northwest Territories',
            'NU' => 'Nunavut',
            'ON' => 'Ontario',
            'PE' => 'Prince Edward Island',
            'QC' => 'Quebec',
            'SK' => 'Saskatchewen',
            'YT' => 'Yukon',
        );
    }

    /*
     * Product Type List
     */
    private function productTypesList()
    {
        return array('print', 'online', '800', 'Package');
    }

    /*
     * Video Status List
     */
    private function videoStatusList()
    {
        return array(
            'Newly Purchased',
            'In Production',
            'Ready for Review',
            'Live',
            'Disabled / Turned off'
        );
    }

    /*
     * Device Type List
     */
    private function deviceTypeList()
    {
        return array(
            'ipad',
            'iphone',
            'tablet',
            'mobile',
            'desktop',
            'android'
        );
    }

    /*
     * Special Type List
     */
    private function specialTypeList()
    {
        return array(
            'shared',
            'video',
            'social',
            'reputation',
            'website',
            'evergreen',
            'featured'
        );
    }

    /*
     * Rate Type List
     */
    private function rateTypeList()
    {
        return array(
            'dollar',
            'from-dollar',
            'dollar-off',
            'percent-off',
            'call-for-rate',
            'online-rate',
        );
    }

    /*
     * Social Type List
     */
    private function socialDataTypeList()
    {
        $dataTypes = [];
        $dataTypes[] = array(
            'name' => 'Facebook',
            'type' => 'facebook'
        );
        $dataTypes[] = array(
            'name' => 'Twiter',
            'type' => 'twitter'
        );
        $dataTypes[] = array(
            'name' => 'Google+',
            'type' => 'googleplus'
        );
        $dataTypes[] = array(
            'name' => 'YouTube',
            'type' => 'youtube'
        );
        return $dataTypes;
    }
}
