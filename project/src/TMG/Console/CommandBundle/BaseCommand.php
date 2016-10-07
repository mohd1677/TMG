<?php

namespace TMG\Console\CommandBundle;

use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Console\Style\SymfonyStyle;
use TMG\Api\ApiBundle\Entity\ImporterReport;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * Base Command.
 *
 * Provides common methods shared by multiple commands.
 */
abstract class BaseCommand extends ContainerAwareCommand
{
    /** @var string The name of the importer */
    protected $name = 'undefined';

    /** @var int */
    protected $startTime;

    /** @var OutputInterface */
    protected $output;

    /** @var InputInterface */
    protected $input;

    /** @var Logger */
    protected $logger;

    /** @var SymfonyStyle */
    protected $io;

    /** @var EntityManager*/
    protected $entityManager;

    /** @var ImporterReport */
    private $importerReport;

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        // execution start time.
        $this->startTime = microtime(true);

        // Get an instance of the entity manager.
        $this->entityManager = $this->getContainer()->get('doctrine')->getManager();

        // Store the output interface where we can access it from other methods
        $this->output = $output;

        // Store the input interface where we can access it from other methods
        $this->input = $input;

        // Logger for loggin to log file instead of stdout
        $this->logger = $this->getContainer()->get('logger');

        // IO for Symfony style rather sub-optimal IO using the OutputInterface directly.
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * Updates any outstanding Importer Reports as well as creating a new
     * instance of ImporterReport for the current execution.
     *
     * @param  string $log
     *
     * @return void
     */
    protected function startImporter($log = null)
    {
        $this->logger->info('Starting '.$this->name.' command.');

        // Check for and update any old importer reports.
        $outdatedReports = $this->entityManager
            ->getRepository('ApiBundle:ImporterReport')
            ->findBy(array(
                'isLatest' => true,
                'name' => $this->name,
            ));

        // If we have any, lets update them accordingly.
        if ($outdatedReports) {
            $this->logger->info('Cleaning up outdated reports');

            foreach ($outdatedReports as $outdatedReport) {
                if ($outdatedReport->getStatus() != 'Completed') {
                    $outdatedReport->setStatus('Incomplete');
                    $outdatedReport->setLog('Failed!');
                }

                $outdatedReport->setIsLatest(false);
            }
        }

        // Create an instance of ImporterReport() to log the current execution.
        /** @var ImporterReport importerReport */
        $this->importerReport = new ImporterReport();

        $this->importerReport
            ->setName($this->name)
            ->setStatus('Started')
            ->setLog($log)
            ->setIsLatest(true)
            ->setReportDate(new DateTime());

        $this->entityManager->persist($this->importerReport);
        $this->entityManager->flush();
    }

    /**
     * Update the importer report and flush doctrine one last time
     *
     * @param  string $log
     *
     * @return void
     */
    protected function stopImporter($log = null)
    {
        $this->io->text("Finishing up...");

        // Update importer report with latest information
        $this->importerReport->setLog($log);
        $this->importerReport->setStatus('Completed');
        $this->importerReport->setCompletedAt(new DateTime());

        // We'll merge in case someone called clear() in a sub class.
        $this->entityManager->merge($this->importerReport);

        // And write changes to the database.
        $this->entityManager->flush();

        $this->logger->info('Finished '.$this->name.' command');
    }
}
