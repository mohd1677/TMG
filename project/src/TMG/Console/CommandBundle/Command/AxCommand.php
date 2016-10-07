<?php

namespace TMG\Console\CommandBundle\Command;

use DOMDocument;
use TMG\Console\CommandBundle\BaseCommand;
use TMG\Console\CommandBundle\Parser\AxParser;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * AX Importer
 *
 * Configuration and orchestration of the AX importer.
 */
class AxCommand extends BaseCommand
{
    /**
     * Configure all the things
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('import:ax')
            ->setDescription('Import account data from AX')
            ->addOption(
                'feedType',
                'f',
                InputOption::VALUE_REQUIRED,
                'The type of import to perform. Can be "hourly", "daily" or "full".',
                'hourly'
            )
            ->addOption(
                'account',
                'a',
                InputOption::VALUE_REQUIRED,
                'Only pull data for the specified account.'
            )
            ->addOption(
                'inputFile',
                'i',
                InputOption::VALUE_REQUIRED,
                'Input file containing XML data to import.'
            )
            ->addOption(
                'pullOnly',
                'p',
                InputOption::VALUE_REQUIRED,
                'Just retrieve the XML data, don\'t actually import anything.',
                'false'
            );

        $this->name = 'AX';
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
        // If an input file was specified, we'll want to be sure we can read it before continuing
        if (($input->getOption('inputFile')) && (!is_readable($input->getOption('inputFile')))) {
            $output->writeln(
                '<error>"' . $input->getOption('inputFile') . '" does not exist or could not be read. '
                . 'Please check that the file exists and that the user running the script can read it.</error>'
            );

            return 1;
        }

        // If the user specified an input file and the --pullOnly option, we need to insult the user somehow
        // because this doesn't make sense.

        // We need to convert the input from string to a boolean value
        $pullOnly = $input->getOption('pullOnly') === 'true' ? true : false;

        if (($input->getOption('inputFile')) && $pullOnly) {
            $output->writeln(
                '<error>It doesn\'t make sense for you to specify an input file and then tell me to only pull the data.'
                . ' Obviously you already have the data. Go home dev, you\'re drunk!</error>'
            );

            return 1;
        }

        // Let the user know what we are doing.
        if ($input->getOption('inputFile')) {
            $output->writeln('<info>Importing contents of ' . $input->getOption('inputFile') . '.</info>');
        }

        if ($pullOnly) {
            $output->writeln('<info>Retrieving ' . $input->getOption('feedType') . ' account data');
        }

        if (!$input->getOption('inputFile')) {
            $output->writeln(
                '<info>Performing ' . $input->getOption('feedType') . ' import of account data.</info>'
            );
        }

        // Start the report for this execution.
        $this->startImporter('Executing...');

        // Load data, either from file or by calling the AX endpoint.
        $data = $this->loadXmlData(
            $input->getOption('feedType'),
            $input->getOption('account'),
            $input->getOption('inputFile'),
            $pullOnly
        );

        // If there is no data, then there was an error or we weren't intended to process it.
        // So We'll gracefully exit here.
        if ($data === true) {
            return 0;
        } elseif ($data === false) {
            return 1;
        }

        // Otherwise, loadXmlData() returns the data and the inputFile in an array.
        // Let's break that up.
        $inputFile = $data['inputFile'];
        $data = $data['data'];

        // Get an instance of the AX Parser to handle the data;
        $parser = new AxParser($this->output, $this->entityManager);

        // Pass the data to the AxParser instance and import the data.
        $result = $parser->setData($data)->import();

        // If the result is false then something went wrong.
        if ($result === false) {
            return 1;
        }

        // If the input file was created by the script and it still exists, delete it.
        if (($inputFile !== true) && (file_exists($inputFile))) {
            unlink($inputFile);
        }

        // Stop and update the report and flush doctrine one last time.
        $this->stopImporter($result);

        // Everything ran successfully!
        return 0;
    }

    /**
     * Load XML Data, either from file or AX endpoint
     *
     * @param  string $feedType         Can be 'hourly', 'daily' or 'full'. Species how much data to request.
     * @param  int    $accountNumber    An AX account number.
     * @param  string $inputFile        Fully qualified path to a file containing XML data to import.
     * @param  bool   $pullOnly         Whether to import the data or just request it from AX and save it.
     *
     * @return bool|array
     */
    private function loadXmlData($feedType, $accountNumber = null, $inputFile = null, $pullOnly = null)
    {
        $this->output->writeln('<info>Loading XML data...</info>');

        // $fileGiven tells us whether to delete the file or not after the import.
        // If the user specifies the file, we don't want to delete it.
        $fileGiven = ($inputFile) ? true : false;

        // If an input file was not specified, we'll need to pull the data from AX.
        if (!$inputFile) {
            $inputFile = $this->getFeedFromAx($feedType, $accountNumber);
        }

        // If we were told to only pull the data, we'll return here without loading the data into memory.
        if ($pullOnly) {
            $this->output->writeln('<info>AX response saved to ' . $inputFile . '</info>');

            return true;
        }

        // Let's load the XML data into memory.
        $xml = simplexml_load_file($inputFile);

        // Make sure we were able to read data from the file.
        if (!$xml) {
            $this->output->writeln('<error>Unable to read data from ' . $inputFile . '</error>');

            return false;
        }

        // Since it's easier to process data as an array, let's convert it.
        // First we re-encode it as JSON.
        $json = json_encode($xml);

        // Then we decode it to get an associative array and convert the keys to upper-case.
        $data = $this->arrayKeysToCase(json_decode($json, true), CASE_UPPER, true);

        return [
            // If the file was given to us, then we don't want to delet it.
            // Otherwise, we'll return the file path so the importer can delete it later.
            'inputFile' => ($fileGiven) ? true : $inputFile,
            'data'      => $data,
        ];
    }

    /**
     * Get data from AX endpoint
     *
     * @param  string  $feedType        Can be 'hourly', 'daily', or 'full'. Specifies how much data to request.
     * @param  integer $accountNumber   An AX account number.
     *
     * @return string|bool              The path to the file in which the output was saved. False if there was an error.
     */
    private function getFeedFromAx($feedType, $accountNumber = null)
    {
        $this->output->writeln('<info>Pulling data from AX...</info>');

        // Define the path to save the temporary file to.
        $filePath = $this->getContainer()->getParameter('kernel.cache_dir');
        $filePath .= '/ax-';
        $filePath .= (empty($accountNumber)) ? '' : $accountNumber . '-';
        $filePath .= $feedType . '.xml';

        // Check to see if the file already exists. If it does, we'll delete it.
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Lets set the start and end dates, based on the feed type.
        $start = '';
        $end = '';
        $minutes = '';

        switch ($feedType) {
            case 'hourly':
                $start = date('Y-m-d', strtotime('-1 week', microtime(true)));
                $end = date('Y-m-d', strtotime('+6 month', microtime(true)));
                $minutes = '60';
                break;
            case 'daily':
                $start = date('Y-m-d', strtotime('-1 week', microtime(true)));
                $end = date('Y-m-d', strtotime('+6 month', microtime(true)));
                break;
            case 'full':
                $start = date('Y-m-d', strtotime('-2 year', microtime(true)));
                $end = date('Y-m-d', strtotime('+2 year', microtime(true)));
                break;
        }

        // Define the URL.
        $url = $this->getContainer()->getParameter('ax_base_url');
        $url .= '?CompanyKey=' . $this->getContainer()->getParameter('ax_company_key');
        $url .= '&dataAreaId=' . $this->getContainer()->getParameter('ax_data_area_id');
        $url .= '&Group=';
        $url .= '&citycode=';
        $url .= '&startDate=' . $start;
        $url .= '&endDate=' . $end;
        $url .= '&mins=' . $minutes;
        $url .= '&AccountNumber=' . $accountNumber;

        // Set up the cURL request.
        $curlHandle = curl_init();
        curl_setopt_array(
            $curlHandle,
            array(
                CURLOPT_URL            => $url,
                CURLOPT_HEADER         => false,
                CURLOPT_HTTPHEADER     => ['Content-Type: application/xml; charset=utf-8'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            )
        );

        // Make the request.
        $result = curl_exec($curlHandle);
        $errono = curl_errno($curlHandle);
        $error = curl_error($curlHandle);

        curl_close($curlHandle);

        // Check to see if there was an error with the request.
        if ($errono) {
            $this->output->writeln('<error>cURL error: ' . $error . '</error>');

            return false;
        }

        // If not, we'll write the response to a file.
        $document = new DOMDocument();
        $document->formatOutput = true;
        $document->loadXML($result);

        $this->output->writeln('<info>Writing data to file...</info>');

        $file = fopen($filePath, 'w');
        fwrite($file, $document->saveXML());
        fclose($file);

        // Now let's check to make sure the file was successfully created.
        if (!file_exists($filePath)) {
            $this->output->writeln('<error>Unkown error writing response to file.</error>');

            return false;
        }

        // If it was, we'll return the path to said file.
        return $filePath;
    }

    private function arrayKeysToCase($array, $case = CASE_UPPER, $recursive = false)
    {
        $array = array_change_key_case($array, $case);

        if ($recursive) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $array[$key] = $this->arrayKeysToCase($array[$key], $case, true);
                }
            }
        }

        return $array;
    }
}
