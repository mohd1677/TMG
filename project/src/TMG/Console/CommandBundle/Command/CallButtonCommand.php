<?php

namespace TMG\Console\CommandBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TMG\Console\CommandBundle\BaseCommand;
use TMG\Console\CommandBundle\Parser\CallButtonParser;
use TMG\UtilitiesBundle\Validators\DateValidator;

class CallButtonCommand extends BaseCommand
{

    /**
     * Configure all the things
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('import:callButton')
            ->setDescription('Import from the CallButton API.')
            ->addOption(
                'startDate',
                'd',
                InputOption::VALUE_REQUIRED,
                'A PHP datetime format representing when to pull from',
                null
            );

        $this->name = 'CallButton';
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
        $start = $input->getOption('startDate');

        if (!DateValidator::validate($start, 'Y-m-d H:i:s') && !is_null($start)) {
            $output->writeln("startDate needs to be in the form 'Y-m-d H:i:s'. $start was given.");
            return 1;
        }

        /** @var CallButtonParser $parser */
        $parser = new CallButtonParser($output, $this->entityManager);
        
        $this->startImporter('Starting...');

        $parser->setStartTime($start);

        $result = $parser->getRecords();

        $output->writeln('<info>'.$result.'</info>');
        
        $this->stopImporter($result);
        
        return 0;
    }
}
