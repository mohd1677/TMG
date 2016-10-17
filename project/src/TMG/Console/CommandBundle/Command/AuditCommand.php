<?php

namespace TMG\Console\CommandBundle\Command;

use JMS\Serializer\SerializationContext;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TMG\Console\CommandBundle\BaseCommand;
use TMG\UtilitiesBundle\Validators\DateValidator;

class AuditCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('generate:auditReport')
            ->setDescription('Generates an audit report for billing.')
            ->addOption(
                'startDate',
                '',
                InputOption::VALUE_REQUIRED,
                'A date to start from in YYYY-MM-DD format.',
                date('Y-m-01')
            )
            ->addOption(
                'endDate',
                '',
                InputOption::VALUE_REQUIRED,
                'A date to end with in YYYY-MM-DD format.',
                date('Y-m-t')
            );

        $this->name = 'Audit Report';
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Start the report for this execution.
        $this->startImporter('Executing...');

        // Get an instance of the container.
        $container = $this->getContainer();

        // Lets get a DateTime object for the start and end dates.
        $startDate = DateValidator::validate($input->getOption('startDate'), 'Y-m-d');
        $endDate = DateValidator::validate($input->getOption('endDate'), 'Y-m-d');

        // If we can't make sense of the start or end dates, we'll exit here.
        if (!$startDate || !$endDate) {
            $output->writeln('<error>Unusable start or end date provided.</error>');

            return 1;
        }

        // Time to get all of the contracts that we *thought* were active during the date range given.
        // We'll start by grabbing the handler.
        $contractHandler = $container->get('tmg.contract.handler');

        // From that, we'll get the query builder.
        $queryBuilder = $contractHandler->getActiveContractsQueryBuilder($startDate, $endDate);

        // This job is intentionally built to share logic with the API endpoint.
        // However, we don't actually have a request object.
        // So we'll manually create "request" parameters that can then be passed to the pagination factory.
        $requestParams = [
            // We have to specify a large number here so that we get back all of the results on one page.
            'count' => '100000',
            'page' => 1,
        ];

        $routeParams = [
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $startDate->format('Y-m-d'),
            // The count is made up here to avoid generating URLs that could lead to enormous requests on the API.
            'count' => 100,
        ];

        $paginatedCollection = $container
            ->get('tmg.pagination_factory')
            ->createCollection(
                $queryBuilder,
                $requestParams,
                'tmg_api_get_active_contract_list',
                $routeParams
            );

        // Because we aren't creating a controller, we can't just return the paginated collection.
        // Instead we need to manually get the serializer.
        $serializer = $container->get('serializer');

        // And we can't use annotations to set serialization context.
        $serializationContext = new SerializationContext();
        $serializationContext->setGroups([
            'All',
            'feedback'
        ]);

        // This is where we serialize the paginated collection.
        $json = $serializer->serialize(
            $paginatedCollection,
            'json',
            $serializationContext
        );

        // Now we can upload the results to a file on S3.
        $s3Client = $this->getContainer()->get('aws.s3');

        $s3Client->upload('reports.travelmediagroup', 'auditReport.json', $json);
    }
}
