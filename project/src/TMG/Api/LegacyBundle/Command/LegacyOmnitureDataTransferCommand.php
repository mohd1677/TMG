<?php

namespace TMG\Api\LegacyBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TMG\Api\ApiBundle\Entity\DeviceType;
use TMG\Api\ApiBundle\Entity\Property;
use TMG\Api\ApiBundle\Entity\Repository\DeviceTypeRepository;
use TMG\Api\ApiBundle\Entity\Repository\PropertyRepository;
use TMG\Api\LegacyBundle\Entity\Analytic as LegacyAnalytic;
use TMG\Api\ApiBundle\Entity\Analytic;
use TMG\Api\LegacyBundle\Entity\Property as LegacyProperty;
use TMG\Api\ApiBundle\Entity\Repository\AnalyticRepository;

class LegacyOmnitureDataTransferCommand extends ContainerAwareCommand
{
    /** @var OutputInterface */
    private $output;

    /** @var EntityManager */
    private $legacyEntityManager;

    /** @var EntityManager */
    private $entityManager;

    /** @var EntityRepository */
    private $legacyAnalyticRepository;

    /** @var AnalyticRepository */
    private $analyticRepository;

    /** @var PropertyRepository */
    private $propertyRepository;

    /** @var DeviceTypeRepository */
    private $deviceTypeRepository;

    /**
     * Initialize some variables. Called before execute()
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $doctrine = $this->getContainer()->get('doctrine');
        $this->legacyEntityManager = $doctrine->getManager('legacy');
        $this->entityManager = $doctrine->getManager();
        $this->legacyAnalyticRepository = $this->legacyEntityManager->getRepository('TMGApiLegacyBundle:Analytic');
        $this->propertyRepository = $this->entityManager->getRepository('ApiBundle:Property');
        $this->deviceTypeRepository = $this->entityManager->getRepository('ApiBundle:DeviceType');
        $this->analyticRepository = $this->entityManager->getRepository('ApiBundle:Analytic');
    }

    /**
     * Configure all the things
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('migrate:analytics')
            ->setDescription('Transfers analytics data from legacy to reloaded.');
    }

    /**
     * The orchestration of the importer
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get the legacy data;
        $legacyRecordsCount = $this->legacyAnalyticRepository
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->getQuery()
            ->getSingleScalarResult();

        $output->writeln('<info>Migrating '.number_format($legacyRecordsCount).' legacy records...</info>');

        // Create a new progress bar,
        $progressBar = new ProgressBar($this->output, $legacyRecordsCount);

        // and start it.
        $progressBar->start();

        // We'll progress this in batches of 100 records. (it's a lot of data)
        $batchSize = 100;

        // Fist get a query that we can iterate over.
        $legacyRecordsQuery = $this->legacyEntityManager->createQuery(
            'SELECT a FROM TMG\Api\LegacyBundle\Entity\Analytic a'
        );

        // Then get an iterable object from it
        $iterableRecords = $legacyRecordsQuery->iterate();

        // and iterate over it.
        foreach ($iterableRecords as $row) {
            /** @var LegacyAnalytic $legacyRecord */
            $legacyRecord = $row[0];

            /** @var LegacyProperty $legacyProperty */
            $legacyProperty = $legacyRecord->getProperty();

            /** @var Property $property */
            $property = $this->propertyRepository->findOneBy(['hash' =>$legacyProperty->getId()]);

            // If we couldn't find a property, then we have a problem. We'll skip this record.
            if (!$property) {
                continue;
            }

            /** @var DeviceType $deviceType */
            $deviceType = $this->deviceTypeRepository->findOneBy(['name' => $legacyRecord->getDeviceType()]);

            if ($property && $deviceType) {
                /** @var Analytic $analytic */
                $analytic = $this->analyticRepository->findOneBy(
                    [
                        'reportDate' => $legacyRecord->getReportDate(),
                        'property' => $property,
                        'device' => $deviceType,
                    ]
                );

                if (!$analytic) {
                    $analytic = new Analytic();

                    $analytic
                        ->setReportDate($legacyRecord->getReportDate())
                        ->setCouponViews($legacyRecord->getCouponViews())
                        ->setDetailViews($legacyRecord->getDetailViews())
                        ->setFeaturedAdClicks($legacyRecord->getFeaturedAdClicks())
                        ->setOnlineRateClicks($legacyRecord->getOnlineRateClicks())
                        ->setProperty($property)
                        ->setDevice($deviceType);

                    $this->entityManager->persist($analytic);
                }
            }

            if (($progressBar->getProgress() % $batchSize) === 0) {
                // Flush records to the new database
                $this->entityManager->flush();

                // And detach everything so it can be garbage collected.
                $this->entityManager->clear();
                $this->legacyEntityManager->clear();
            }

            $progressBar->advance();
        }

        // Flush one last time for things that don't make a full batch.
        $this->entityManager->flush();

        return 0;
    }
}
