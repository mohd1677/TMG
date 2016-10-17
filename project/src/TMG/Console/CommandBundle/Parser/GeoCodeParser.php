<?php

namespace TMG\Console\CommandBundle\Parser;

use TMG\Api\ApiBundle\Entity\Address;
use TMG\Api\ApiBundle\Entity\State;
use TMG\Api\ApiBundle\Entity\Country;
use TMG\Api\ApiBundle\Entity\PostalCode;
use TMG\Console\CommandBundle\BaseParser;

/**
 * GeoCode Parser
 *
 * Parses data from Google Maps Geocoding API and persists it to the database.
 */
class GeoCodeParser extends BaseParser
{
    /**
     * Parse the results and update the address object
     *
     * @param  array                             $geoData
     * @param  \TMG\Api\ApiBundle\Entity\Address $address
     *
     * @return bool|string
     */
    public function parse(array $geoData, Address $address)
    {
        // Let's pull the lat and long out of the results
        $latitude = $geoData['geometry']['location']['lat'];
        $longitude = $geoData['geometry']['location']['lng'];

        // And update the address
        $address->setLatitude($latitude);
        $address->setLongitude($longitude);

        // We need to check that the postal code exists before we can update the hash.
        if (!$address->getPostalCode()) {
            $postalCode = $this->getPostalCode($geoData['address_components']);

            if (!$postalCode) {
                return 'noPostal';
            }

            $address->setPostalCode($postalCode);
        }

        // Same story for the state
        if (!$address->getState()) {
            $state = $this->getState($geoData['address_components']);

            if (!$state) {
                return 'noState';
            }

            $address->setState($state);
        }

        if (!$address->getCountry()) {
            $country = $this->getCountry($geoData['address_components']);

            if (!$country) {
                return 'noCountry';
            }

            $address->setCountry($country);
        }

        // If the new hash of this address already exists on another object,
        // we can safely discard this one because we won't be able to persist it anyways
        $newHash = $address->updateHash();

        $existingAddress = $this->entityManager->getRepository('ApiBundle:Address')
            ->findOneBy(['hash' => $newHash]);

        if ($existingAddress) {
            $this->entityManager->detach($address);

            return 'newVersion';
        }

        // Otherwise we can update this particular object in the database
        $this->entityManager->flush();

        return true;
    }

    private function getCountry(array $addressComponents)
    {
        $countryData = $this->getComponent('country', $addressComponents);

        // If we have it, we'll search for an existing entry in the database
        // In most cases, we'll find a hit
        if ($countryData) {
            $countryCode = $countryData['shortName'];
            $countryName = $countryData['longName'];

            $country = $this->entityManager->getRepository('ApiBundle:State')
                ->findOneBy(['code' => $countryCode]);

            // If we don't get a hit, we'll create the new country object
            if (!$country) {
                $country = new Country();
                $country
                    ->setName($countryName)
                    ->setCode($countryCode);

                $this->entityManager->persist($country);
            }

            return $country;
        }

        // If the results don't contain country data, we'll just return false
        return false;
    }

    /**
     * Get state data from Geocode results
     *
     * @param  array $addressComponents
     *
     * @return State|bool
     */
    private function getState(array $addressComponents)
    {
        // Let's search the results for the state component
        $stateData = $this->getComponent('administrative_area_level_1', $addressComponents);

        // If we have it, we'll search for an existing entry in the database
        // In most cases, we'll find a hit
        if ($stateData) {
            $stateCode = $stateData['shortName'];
            $stateName = $stateData['longName'];

            $state = $this->entityManager->getRepository('ApiBundle:State')
                ->findOneBy(['name' => $stateName]);

            // If we don't get a hit, we'll create the new state object
            if (!$state) {
                $state = new State();
                $state
                    ->setName($stateName)
                    ->setAbbreviation($stateCode);

                $this->entityManager->persist($state);
            }

            return $state;
        }

        // If the results doesn't contain state data, we'll just return false
        return false;
    }

    /**
     * Get zip code data from the Geocode results
     *
     * @param  array $addressComponents
     *
     * @return PostalCode|bool
     */
    private function getPostalCode($addressComponents)
    {
        // Let's get the zip code from the results
        $zipCode = $this->getComponent('postal_code', $addressComponents)['shortName'];
        // As well as the suffix (or +four)
        $zipCodeSuffix = $this->getComponent('postal_code_suffix', $addressComponents)['shortName'];

        $postalCode = null;

        // If we have both parts, we can query for an existing object using the "full" zip code.
        if ($zipCode && $zipCodeSuffix) {
            $postalCode = $this->entityManager->getRepository('ApiBundle:PostalCode')
                ->findOneBy(['codeFull' => $zipCode.'-'.$zipCodeSuffix]);
        // Otherwise, we'll query for the base zip code. We only do this if we don't have the suffix
        // because we can't update these objects. If the existing PostalCode object doesn't have the suffix
        // we can't add it. Instead we either need to do the query above to get the appropriate object
        // or create a new one if it doesn't exist.
        } elseif ($zipCode && !$zipCodeSuffix) {
            $postalCode = $this->entityManager->getRepository('ApiBundle:PostalCode')
                ->findOneBy(['code' => $zipCode]);
        }

        // At this point we know we either have one of the following:
        //
        // * No zip code
        // * An existing PostalCode object
        //
        // We check first that we DONT have an existing object, and then that
        // there is in fact the minimum required data to create a new object
        if (!$postalCode && $zipCode) {
            $postalCode = new PostalCode();
            $postalCode->setCode($zipCode);

            $this->entityManager->persist($postalCode);
        }

        // Here we know we have an object (persisted or not) we just need to check whether we have a suffix to add
        // to the new object.
        if ($zipCodeSuffix && $postalCode) {
            $postalCode->setCodeFull($zipCode.'-'.$zipCodeSuffix);
        }

        // If we were able to find or create an object, we'll return it
        if ($postalCode) {
            return $postalCode;
        }

        // Otherwise, we return false.
        return false;
    }

    /**
     * Get an individual component from the results
     *
     * @param  string $searchName
     * @param  array  $components
     *
     * @return array|bool
     */
    private function getComponent($searchName, array $components)
    {
        // Loop over the components and look for one that matches
        foreach ($components as $component) {
            // Sometimes there are multiple types.
            foreach ($component['types'] as $type) {
                if ($type === $searchName) {
                    return [
                        'shortName' => $component['short_name'],
                        'longName' => $component['long_name'],
                    ];
                }
            }
        }

        return false;
    }
}
