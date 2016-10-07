<?php

namespace TMG\Console\CommandBundle\Command;

use TMG\Api\ApiBundle\Entity\Address;
use TMG\Console\CommandBundle\BaseCommand;
use TMG\Console\CommandBundle\Parser\GeoCodeParser;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * GeoCode Command
 *
 * Configuration and orchestration of the GeoCode command.
 */
class GeoCodeCommand extends BaseCommand
{
    /** @var string The base URL for the Geocoding API */
    private $googleGeocodeUrl = 'https://maps.googleapis.com/maps/api/geocode/json?';

    /** @var float The last time the Google API was requested */
    private $lastApiRequest;
    /**
     * Configure all the things
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('import:geocode')
            ->setDescription('GeoCode all addresses');

        $this->name = 'GeoCode';

        $this->lastApiRequest = microtime(true);
    }

    /**
     * The orchestration of the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $parser = new GeoCodeParser($this->output, $this->entityManager);

        $unencodedAddresses = $this->entityManager
            ->getRepository('ApiBundle:Address')
            ->getUnencodedAddresses();

        $this->output->writeln('<info>GeoCoding addresses...</info>');

        // Create a new progress bar
        $progress = new ProgressBar($this->output, count($unencodedAddresses));

        // Start the progress bar
        $progress->start();

        // Loop over all of the un-encoded addresses and attempt to encode them
        // I haven't found a way to limit the number of results so we I need to limit the run to < 1000 queries
        // This should leave some room for the other importers to do their thing.
        $index = 0;

        foreach ($unencodedAddresses as $address) {
            $index++;
            if ($index >= 1000) {
                break;
            }

            $geoData = $this->getGeoData($address);

            $parserResult = false;

            if ($geoData) {
                $parserResult = $parser->parse($geoData, $address);
            }

            switch ($parserResult) {
                case 'newVersion':
                    $progress->clear();
                    $this->output->writeln("\n<error>An updated version of this address already exists.</error>");
                    $progress->display();
                    break;
                case 'noPostal':
                    $progress->clear();
                    $this->output->writeln("\n<error>Address is missing zip code.</error>");
                    $progress->display();
                    break;
                case 'noState':
                    $progress->clear();
                    $this->output->writeln("\n<error>Address is missing state.</error>");
                    $progress->display();
                    break;
                case 'noCountry':
                    $progress->clear();
                    $this->output->writeln("\n<error>Address is missing country</error>");
                    $progress->display();
                    break;
                case false:
                    $progress->clear();
                    $this->output->writeln("\n<error>An unknown error occurred.</error>");
                    $progress->display();
                    break;
            }

            $progress->advance();
        }

        $this->stopImporter($progress->getProgress().' addresses processed.');

        $progress->finish();

        return 0;
    }

    /**
     * @param Address $address
     *
     * @return bool|array
     */
    public function getGeoData($address)
    {
        $url = $this->googleGeocodeUrl.'key='.$this->getContainer()->getParameter('google_api_key');
        $url .= '&address='.urlencode($address);

        // Set up the cURL request.
        $curlHandle = curl_init();
        curl_setopt_array(
            $curlHandle,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
            )
        );

        // Make the request.
        if (microtime(true) - $this->lastApiRequest < 0.2) {
            sleep(1);
        } else {
            $this->lastApiRequest = microtime(true);
        }

        $result = curl_exec($curlHandle);
        $errno = curl_errno($curlHandle);
        $error = curl_error($curlHandle);

        curl_close($curlHandle);

        // Check to see if there was an error with the request.
        if ($errno) {
            $this->output->writeln('<error>cURL error: ' . $error . '</error>');

            return false;
        }

        $result = json_decode($result, true);

        return $result['results'][0];
    }
}
