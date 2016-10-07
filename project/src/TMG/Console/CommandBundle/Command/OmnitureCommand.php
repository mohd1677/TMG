<?php

namespace TMG\Console\CommandBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TMG\Console\CommandBundle\BaseCommand;
use TMG\Console\CommandBundle\Parser\OmnitureParser;
use TMG\UtilitiesBundle\Validators\DateValidator;

class OmnitureCommand extends BaseCommand
{

    /**
     * Configure all the things
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('import:omniture')
            ->setDescription('Import website data from Omniture')
            ->addOption(
                'startDate',
                'd',
                InputOption::VALUE_REQUIRED,
                'A date to start from in YYYY-MM-DD format.',
                null
            );

        $this->name = 'Omniture';
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
        //start the importer
        $this->startImporter('Executing...');

        $startDate = $input->getOption('startDate');

        if (!is_null($startDate) && !DateValidator::validate($startDate, 'Y-m-d')) {
            $output->writeln('Bad date format given. Expected format is YYYY-MM-DD');
            $output->writeln("'$startDate' was given.");

            return 1;
        }

        $parser = new OmnitureParser($output, $this->entityManager, $this->getContainer());

        $log = $parser->queueAndProcessReports($startDate);

        $this->stopImporter($log);

        return 0;
    }
}
