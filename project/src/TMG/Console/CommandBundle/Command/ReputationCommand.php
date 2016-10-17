<?php

namespace TMG\Console\CommandBundle\Command;

use DateTime;
use Exception;
use DateInterval;
use TMG\Console\CommandBundle\BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TMG\Console\CommandBundle\Parser\ReputationParser;
use TMG\Api\ApiBundle\Entity\Repository\ReputationRepository;

/**
 * Reputation Importer
 *
 * Configuration and orchestration of the reputation importer.
 */
class ReputationCommand extends BaseCommand
{
    /**
     * Configure all the things
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('import:reputation')
            ->setDescription('Import reputation data from engage')
            ->addOption(
                'account',
                'a',
                InputOption::VALUE_REQUIRED,
                'Only pull data for the specified account.'
            )
            ->addOption(
                'startDate',
                'd',
                InputOption::VALUE_REQUIRED,
                'A date to start from in YYYY-MM format. The day is not necessary for the purposes of this importer.',
                date('Y-m')
            )
            ->addOption(
                'clearingHouse',
                'c',
                InputOption::VALUE_REQUIRED,
                'If true, the importer will re-pull the data from the previous month.',
                'false'
            )
            ->addOption(
                'type',
                't',
                InputOption::VALUE_REQUIRED,
                'Can be `1` for resolve contracts or `2` for standard reputation contracts.',
                '0'
            );

        $this->name = 'Reputation';
    }

    /**
     * The orchestration of the importer
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Start the report for this execution.
        $this->startImporter('Executing...');

        // Default this to null. We'll use it later.
        $property = null;

        // If an account was specified, then we need to ensure that we have a
        // property with the given account number.
        if ($input->getOption('account')) {
            $property = $this->entityManager->getRepository('ApiBundle:Property')
                ->findOneBy(['axNumber' => $input->getOption('account')]);

            // If we didn't find a property or the property doesn't have a currently
            // active contract, let's go ahead and exit.
            if (!$property) {
                $output->writeln('<error>'.$input->getOption('account').' could not be found!');

                return 1;
            }

            if (!$property->getReputation() || !$property->getReputation()->isActive()) {
                $output->writeln(
                    '<error>'
                    .$input->getOption('account')
                    ." doesn't have a currently active reputation contract!</error>"
                );

                return 1;
            }
        }

        // Lets get a DateTime object for the start date.
        try {
            $startDate = new DateTime($input->getOption('startDate'));
        } catch (Exception $exception) {
            $output->writeln('<error>'.$exception->getMessage().'!</error>');

            return 1;
        }

        // If for some reason we didn't end up where we expected, we'll exit.
        if (!$startDate || $startDate > new DateTime()) {
            $output->writeln('<error>Unusable start date provided!</error>');

            return 1;
        }

        // --clearingHouse can only be either true or false. If it's anything else, we'll exit.
        if (($input->getOption('clearingHouse') != "true") && ($input->getOption('clearingHouse') != "false")) {
            $output->writeln('<error>--clearingHouse must either be true or false!</error>');

            return 1;
        }

        // If we are performing a clearingHouse execution, we'll set the startDate to last month.
        if ($input->getOption('clearingHouse') === 'true') {
            $startDate->sub(new DateInterval('P1M'));
        }

        // Lets get an array of reputation accounts to process.
        // We'll pass in the type of execution, to determine which accounts we get back.
        // We'll also pass in a single property if we have one and get the reputation record back in an array.
        $reputationAccounts = $this->getAccounts(
            $this->input->getOption('type'),
            $property
        );

        if (!$reputationAccounts) {
            $output->writeln('<info>No reputation accounts to process!</info>');

            return 0;
        }

        $parser = new ReputationParser($output, $this->entityManager);

        $result = $parser->processAccounts($reputationAccounts, $startDate);

        // If the result is false then something went wrong.
        if ($result === false) {
            return 1;
        }

        // Stop and update the report and flush doctrine one last time.
        $this->stopImporter($result);

        // Everything ran successfully!
        return 0;
    }

    /**
     * Get reputation accounts that need to be updated
     *
     * @param int $type
     * @param  \TMG\Api\ApiBundle\Entity\Property $property
     *
     * @return array|bool
     */
    private function getAccounts($type, $property = null)
    {
        /** @var ReputationRepository $reputationRepository */
        $reputationRepository = $this->entityManager->getRepository('ApiBundle:Reputation');

        // If we were given a property, lets give it's reputation back in an array.
        if ($property) {
             return [$property->getReputation()];
        } else {
            // Otherwise we'll get all of the currently active reputation accounts.
            $resolveAccounts = $reputationRepository->getActiveResolveAccounts();

            $reputationAccounts = $reputationRepository->getActiveReputationAccounts();
        }

        // We need to determine which accounts to update based on execution type (Command Line Option)
        switch ($type) {
            case 1:
                $reputations = $resolveAccounts;
                break;
            case 2:
                $reputations = $reputationAccounts;
                break;
            default:
                $reputations = array_merge($resolveAccounts, $reputationAccounts);
                break;
        }

        // We'll immediately clear the entity manager and merge each account
        // back in as we update it in the parser. (Speed?)
        $this->entityManager->clear();

        if (!count($reputations) > 0) {
            return false;
        } else {
            return $reputations;
        }
    }
}
