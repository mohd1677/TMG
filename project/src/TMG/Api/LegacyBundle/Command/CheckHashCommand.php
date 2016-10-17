<?php

namespace TMG\Api\LegacyBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use TMG\Api\ApiBundle\Entity\Property;
use TMG\Api\ApiBundle\Entity\Repository\PropertyRepository;

class CheckHashCommand extends ContainerAwareCommand
{
    /** @var OutputInterface */
    private $output;

    /** @var Container */
    private $container;

    /** @var EntityManager */
    private $entityManager;

    /** @var EntityManager */
    private $legacyEntityManager;

    /** @var PropertyRepository */
    private $propertyRepository;

    /** @var \TMG\Api\LegacyBundle\Entity\Repository\PropertyRepository */
    private $legacyPropertyRepository;

    /** {@inheritdoc} */
    protected function configure()
    {
        $this
            ->setName('check:hashes')
            ->setDescription('Checks for mismatches in legacy and reloaded properties tables.')
            ->addOption(
                'account',
                'a',
                InputOption::VALUE_REQUIRED,
                'Check a specific property.'
            );
    }

    /** {@inheritdoc} */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->container = $this->getContainer();
        $doctrine = $this->container->get('doctrine');
        $this->entityManager = $doctrine->getManager();
        $this->legacyEntityManager = $doctrine->getManager('legacy');
        $this->propertyRepository = $this->entityManager->getRepository('ApiBundle:Property');
        $this->legacyPropertyRepository = $this->legacyEntityManager->getRepository('TMGApiLegacyBundle:Property');
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Default this to null. We'll use it later.
        $legacyProperties = null;

        // If an account was specified, then we need to ensure that we have a
        // property with the given account number.
        if ($input->getOption('account')) {
            $legacyProperties = [
                $this->legacyPropertyRepository
                    ->findOneBy(['axAccountNumber' => $input->getOption('account')])
                ];

            // If we didn't find a property, let's go ahead and exit.
            if (!$legacyProperties) {
                $output->writeln('<error>'.$input->getOption('account').' could not be found!');

                return 1;
            }
        }

        if (!$legacyProperties) {
            $legacyProperties = $this->legacyPropertyRepository->findAll();
        }

        if (!$legacyProperties) {
            $output->writeln(
                '<error>No properties were found in the legacy database... (this is likely a serious problem.)</error>'
            );

            return 1;
        }

        $this->check($legacyProperties);

        return 0;
    }

    /**
     * Parse the legacy properties and check to see if they match up against their reloaded doppelgangers.
     *
     * @param array $legacyProperties
     *
     * @return void
     */
    private function check(array $legacyProperties)
    {
        // Let the user know what is going on.
        $this->output->writeln('<info>Checking '.count($legacyProperties).' properties...</info>');

        // Create a new progress bar.
        $progress = new ProgressBar($this->output, count($legacyProperties));

        // Start said progress bar.
        $progress->start();

        $skippedProperties = 0;
        $missingProperties = 0;
        $misMatchedProperties = 0;

        // Loop over each legacy property and make sure the hashes match.
        /** @var \TMG\Api\LegacyBundle\Entity\Property $legacyProperty */
        foreach ($legacyProperties as $legacyProperty) {
            // If the legacy property doesn't have an AX number, it means the account hasn't been active since the
            // switch from E1 to AX accounting.
            if (!$legacyProperty->getAxAccountNumber()) {
                // We won't even bother logging old accounts.
                // Advance the progress bar.
                $progress->advance();

                $skippedProperties++;

                // and go to the next property.
                continue;
            }

            /** @var Property $reloadedProperty */
            $reloadedProperty = $this->propertyRepository
                ->findOneBy(['axNumber' => $legacyProperty->getAxAccountNumber()]);

            // If we didn't find a reloaded property, we'll log the event and go to the next property.
            if (!$reloadedProperty) {
                // Hide the progress bar.
                $progress->clear();

                // log the event.
                $this->output->writeln(
                    '<error>No record found for '
                    .$legacyProperty->getAxAccountNumber()
                    .' in the reloaded table.</error>'
                );

                // Show the progress bar.
                $progress->display();

                // Advance the progress bar.
                $progress->advance();

                $missingProperties++;

                // and go to the next property.
                continue;
            }

            // Now the real business.
            if ($legacyProperty->getId() != $reloadedProperty->getHash()) {
                // Hide the progress bar.
                $progress->clear();

                // log the event.
                $this->output->writeln(
                    '<error>The legacy `id` for '
                    .$legacyProperty->getAxAccountNumber()
                    .' does not match the reloaded `hash`!</error>'
                );

                // Show the progress bar.
                $progress->display();

                // Advance the progress bar.
                $progress->advance();

                // Count the mis-match.
                $misMatchedProperties++;

                // and go to the next property.
                continue;
            }

            $progress->advance();
        }

        // Move the progress bar to 100%
        $progress->finish();

        // and hide it
        $progress->clear();

        $this->output->writeln(
            "\n<info>There are "
            .$misMatchedProperties
            ." properties that don't match up and "
            .$missingProperties
            ." missing records. I skipped "
            .$skippedProperties
            ." properties because they were missing AX numbers in the legacy table.</info>\n"
        );
    }
}
