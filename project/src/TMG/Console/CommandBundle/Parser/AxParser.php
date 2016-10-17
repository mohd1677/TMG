<?php

namespace TMG\Console\CommandBundle\Parser;

use DateTime;
use TMG\Api\ApiBundle\Entity\Books;
use TMG\Api\ApiBundle\Entity\Address;
use TMG\Api\ApiBundle\Entity\Products;
use TMG\Api\ApiBundle\Entity\Contract;
use TMG\Api\ApiBundle\Entity\Property;
use TMG\Api\ApiBundle\Entity\SalesRep;
use TMG\Api\ApiBundle\Entity\PostalCode;
use TMG\Api\ApiBundle\Entity\ProductTypes;
use TMG\Console\CommandBundle\BaseParser;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * AxParser
 *
 * Parses data from AX and persists it to the database.
 */
class AxParser extends BaseParser
{
    /** @var array Holds the data to be processed */
    private $data;

    /**
     * Set data for the parser to process.
     *
     * @param array $data Associative array containing data that needs to be imported.
     *
     * @return boolean|AxParser Returns false if the data type passed in, is not an array.
     */
    public function setData($data = null)
    {
        // Make sure an array of data was passed in
        if (!is_array($data)) {
            $this->output->writeln('<error>Invalid data passed as argument to setData() method.</error>');

            return false;
        }

        // Save the data to a more accessible location.
        $this->data = $data;

        return $this;
    }

    /**
     * The main flow of the importer. Parse each of the assets and update the database.
     *
     * @return string
     */
    public function import()
    {
        if (!array_key_exists('ACCOUNT', $this->data['ORDERS'])) {
            $this->output->writeln('<info>No changes to process.</info>');

            return 'No changes to process.';
        }

        $this->output->writeln('<info>Importing and updating records...</info>');

        // Create a new progress bar.
        $progress = new ProgressBar($this->output, count($this->data['ORDERS']['ACCOUNT']));

        // Start the progress bar.
        $progress->start();

        // Loop over the data and process each contract.
        foreach ($this->data['ORDERS']['ACCOUNT'] as $account) {
            // We'll need the billing address before we can create the property.
            $billingAddress = $this->getAddress(
                $account['BILLINGADDRESSLINE1'],
                $account['BILLINGCITY'],
                $account['BILLINGSTATE'],
                $account['BILLINGZIPCODE'],
                $account['BILLINGCOUNTRY'],
                $account['INTERSTATE'],
                $account['EXIT']
            );

            // We need to flush here, so that the second address doesn't end up attempting to create
            // a duplicate address and cause an integrity constraint vilation.
            $this->entityManager->flush();

            // Same story for property address.
            $propertyAddress = $this->getAddress(
                $account['ADDRESSLINE1'],
                $account['CITY'],
                $account['STATE'],
                $account['ZIPCODE'],
                $account['COUNTRY'],
                $account['INTERSTATE'],
                $account['EXIT']
            );

            // Let's create or update the property. Instead of only passing specific bits of data here,
            // we'll go ahead and pass the whole account due to the number of bits of data needed to
            // generate or update the property.
            $property = $this->getProperty($account, $propertyAddress, $billingAddress);

            // Let's get the the book. We'll need it to create the contract.
            $book = $this->getBook($account['GUIDENAME'], $account['ITEMDEPARTMENT']);

            // Same story for product.
            $product = $this->getProduct(
                $account['ITEM'],
                $account['TYPE'],
                $account['ADSIZE'],
                $account['ITEMDESC']
            );

            // We also need a sales rep before we can create a contract.
            $salesRep = $this->getSalesRep($account['SRNAME'], $account['SRCODE']);

            // Finally, lets put together the contract. Again, we'll just pass the whole
            // account, due to the number of data bits needed.
            $this->getContract($account, $book, $product, $property, $salesRep);

            // Flush database changes and clear the entity manager.
            $this->entityManager->flush();
            $this->entityManager->clear();

            $progress->advance();
        }

        return $progress->getProgress() . ' of ' . $progress->getMaxSteps() . ' processed.';
    }

    /**
     * Get book or create it if it doesn't exist
     *
     * @param  string $guideName       Name of book
     * @param  string $itemDepartment  Department name
     *
     * @return \TMG\Api\ApiBundle\Entity\Books
     */
    private function getBook($guideName, $itemDepartment)
    {
        // Search for a book by name.
        $book = $this->entityManager->getRepository('ApiBundle:Books')
            ->findOneBy(['name' => $guideName]);

        // If we don't find one, let's create it.
        if (!$book) {
            $book = new Books();
            $book
                ->setName($guideName)
                ->setCode($itemDepartment);

            $this->entityManager->persist($book);
        }

        return $book;
    }

    /**
     * Get product or create it if it does not exist
     *
     * @param  string $productCode
     * @param  string $productType
     * @param  string $adSize
     * @param  string $productDescription
     *
     * @return Products;
     */
    private function getProduct($productCode, $productType = null, $adSize = null, $productDescription = null)
    {
        // Search for a product by product code.
        $product = $this->entityManager->getRepository('ApiBundle:Products')
            ->findOneBy(['code' => $productCode]);

        // If we don't find one, let's create it.
        if (!$product) {
            $productCategory = $this->getProductCategory($productCode, $productDescription);

            $product = new Products();
            $product
                ->setCode($productCode)
                ->setType($productCategory)
                ->setTypeDescription($productType)
                ->setDescription($productDescription)
                ->setAdSize($this->valueOrNull($adSize));

            $this->entityManager->persist($product);
        }

        return $product;
    }

    /**
     * Get product type
     *
     * @param  string $productCode
     * @param  string $productDescription
     *
     * @return null|ProductTypes Will return null if the product type can not be identified.
     */
    private function getProductCategory($productCode, $productDescription = null)
    {
        // Get an instance of the repo, we'll be using it a few times.
        $productTypeRepo = $this->entityManager->getRepository('ApiBundle:ProductTypes');

        if (strpos($productCode, '800') !== false) {
            $productCategory = $productTypeRepo->findOneBy(['type' => '800']);
        } elseif (strtolower($productDescription) == 'package') {
            $productCategory = $productTypeRepo->findOneBy(['type' => 'package']);
        } elseif (strpos($productDescription, 'Print') !== false) {
            $productCategory = $productTypeRepo->findOneBy(['type' => 'print']);
        } else {
            $productCategory = $productTypeRepo->findOneBy(['type' => 'online']);
        }

        if (!$productCategory) {
            return null;
        }

        return $productCategory;
    }

    /**
     * Get an address or create one.
     *
     * @param  String $line1
     * @param  string $city
     * @param  string $stateAbbreviation
     * @param  string $zipCode
     * @param  string $countryCode
     * @param  string $interstate
     * @param  string $exit
     * @param  string $line2
     *
     * @return null|\TMG\Api\ApiBundle\Entity\Address
     */
    private function getAddress(
        $line1,
        $city,
        $stateAbbreviation,
        $zipCode,
        $countryCode,
        $interstate,
        $exit,
        $line2 = null
    ) {
        // Line 1 and City are required fields. We can't generate an address without them
        // so we'll just return null here, if they aren't available.
        if (!$line1 || !$city) {
            return null;
        }

        // We don't need the zip code to build the address but it is used in generating the hash so having it could make
        // a difference.
        $postalCode = $this->getPostalCode($zipCode);

        // Same story with the state. It's not required but having it could make a difference.
        $state = $this->getState($stateAbbreviation);

        // And the country.
        $country = $this->getCountry($countryCode);

        // Then we can build an address object and get a hash.
        $address = new Address();

        $address
            ->setLine1($line1)
            ->setCity($city)
            ->setState($state)
            ->setPostalCode($postalCode)
            ->setCountry($country);

        // We check for the remaining fields before setting them to avoid array-to-string exceptions due to the fact
        // that they aren't always provided in the AX feed. Again, not having them isn't an issue, but it can affect
        // the hash.
        if ($interstate) {
            $address->setInterstateNumber($interstate);
        }

        if ($exit) {
            $address->setInterstateExit($exit);
        }

        if ($line2) {
            $address->setLine2($line2);
        }

        // updateHash() generates the hash, adds it to the entity and returns it. We'll use this for looking up
        // an existing address.
        $addressHash = $address->updateHash();

        // Lets go ahead and do that now.
        $existingAddress = $this->entityManager->getRepository('ApiBundle:Address')
            ->findOneBy(['hash' => $addressHash]);

        // If we already have a matching address, we'll use it instead of creating a new one.
        if ($existingAddress) {
            $address = $existingAddress;
        }

        if (!$this->entityManager->contains($address)) {
            $this->entityManager->persist($address);
        }

        return $address;
    }

    /**
     * Get a postal code or create it if if does not exist
     *
     * @param  string $zipCode Zip or Zip+4
     *
     * @return PostalCode
     */
    private function getPostalCode($zipCode)
    {
        $zip = trim($zipCode);
        $zipParts = explode('-', $zip);

        if (count($zipParts) > 1) {
            $zip = $zipParts[0];

            // Piece it together manually rather than using implode() in case there
            // were too many parts. We assume that the correct solution is the first two parts.
            $zipFull = $zipParts[0] . '-' . $zipParts[1];
        }

        // We want to search for a postal code using the zip+4 by default.
        // It's a more specific postal area.
        if (isset($zipFull)) {
            $postalCode = $this->entityManager->getRepository('ApiBundle:PostalCode')
                ->findOneBy(['codeFull' => $zipFull]);
        }

        // If we can't find one, we'll search using just the zip.
        // We exclude any that have a full zip because it might not be the right +4.
        // The +4 is a subdivision of a zip code.
        if (!isset($postalCode)) {
            $postalCode = $this->entityManager->getRepository('ApiBundle:PostalCode')
                ->findOneBy(array(
                    'code' => $zip,
                    'codeFull' => null
                ));
        }

        // If we still don't have one, we'll need to create one.
        if (!$postalCode) {
            $postalCode = new PostalCode();
            $postalCode->setCode($zip);

            // If we have the full zip, we'll add it to the record.
            // We can only do this if it's a new record because we don't want to update the address for properties that
            // are using records that are missing the +4.
            if (isset($zipFull)) {
                $postalCode->setCodeFull($zipFull);
            }

            // Persist the entity and write it to the database.
            $this->entityManager->persist($postalCode);
        }

        return $postalCode;
    }

    /**
     * Get state from abbreviation
     *
     * @param  string $stateAbbreviation
     *
     * @return \TMG\Api\ApiBundle\Entity\State
     */
    private function getState($stateAbbreviation = null)
    {
        // We have to check it to be sure it's not an empty array.
        if (!$stateAbbreviation) {
            return null;
        }

        $state = $this->entityManager->getRepository('ApiBundle:State')
            ->findOneBy(['abbreviation' => $stateAbbreviation]);

        if (!$state) {
            return null;
        }

        return $state;
    }

    /**
     * Get country from country code
     *
     * @param  string $countryCode
     *
     * @return \TMG\Api\ApiBundle\Entity\Country
     */
    private function getCountry($countryCode)
    {
        // We have to check it to be sure it's not an empty array.
        if (!$countryCode) {
            return null;
        }

        $country = $this->entityManager->getRepository('ApiBundle:Country')
            ->findOneBy(['code' => $countryCode]);

        if (!$country) {
            return null;
        }

        return $country;
    }

    /**
     * Get property or create it if necessary
     *
     * @param  array                      $account
     * @param  Address $propertyAddress
     * @param  Address $billingAddress
     *
     * @return null|Property
     */
    private function getProperty($account, $propertyAddress = null, $billingAddress = null)
    {
        // Some required fields to create a property.
        if ((!$account['ACCOUNTNO']) || (!$account['NAME'])) {
            return null;
        }

        // Get an existing property if there is one.
        $property = $this->entityManager->getRepository('ApiBundle:Property')
            ->findOneBy(['axNumber' => $account['ACCOUNTNO']]);

        // If not, create one.
        if (!$property) {
            $property = new Property();

            $property
                ->setAxNumber($account['ACCOUNTNO'])
                ->setRateLock(false)
                ->setForceLive(false)
                ->setSmsEnabled(false);

            $this->entityManager->persist($property);
        }

        // Set some basic details.
        $property
            ->setName($account['NAME'])
            ->setAddress($propertyAddress)
            ->setBillingAddress($billingAddress);

        // Notice that we call valueOrNull() on each of the following fields.
        // This is because in some cases, the field may be blank. When we converted the data to an
        // associative array (In AxCommand), it converted empty elements to empty arrays.
        // If that happened to any of the nullable fields, we need to set it to null to avoid
        // array-to-string conversion exceptions.
        //
        // This was implemented in valueOrNull() to reduce NPath complexity.
        $property
            ->setPropertyNumber($this->valueOrNull($account['E1ACCOUNTNO']))
            ->setContactName($this->valueOrNull($account['CONTACTNAME']))
            ->setEmail($this->valueOrNull($account['CONTACTEMAIL']))
            ->setFax($this->valueOrNull($account['CONTACTFAX']))
            ->setAccountPhone($this->valueOrNull($account['MAINPHONE']))
            ->setSendEmail($this->valueOrNull($account['SENDEMAIL'], true))
            ->setSendFax($this->valueOrNull($account['SENDEMAIL'], true));

        return $property;
    }

    /**
     * Returns the $value if there is a value. Otherwise, returns null.
     * This is meant to handle empty arrays and reduce NPath complexity by removing
     * ternary statements that would otherwise be inside of method calls.
     *
     * @param  mixed $value
     * @param  bool  $boolean If true, will return false if value is missing.
     *
     * @return mixed
     */
    private function valueOrNull($value = null, $boolean = null)
    {
        if ($boolean) {
            return ($value) ? $value : false;
        }

        return ($value) ? $value : null;
    }

    private function getSalesRep($repName, $repId)
    {
        $rep = $this->entityManager->getRepository('ApiBundle:SalesRep')
            ->findOneBy(['code' => $repId]);

        if (!$rep) {
            $rep = new SalesRep();
            $rep
                ->setCode($repId)
                ->setName($repName);

            $this->entityManager->persist($rep);
        }

        return $rep;
    }

    private function getContract($account, $book, $product, $property, $salesRep)
    {
        $startDate = new DateTime($account['STARTDATE']);
        $endDate = new DateTime($account['ENDDATE']);
        $now = new DateTime();

        // Get an instance of the contract repository since we'll be using it multiple times.
        $contractRepository = $this->entityManager->getRepository('ApiBundle:Contract');

        $contract = null;
        // First, we'll look for an existing contract using the VERECID, which is
        // a contract's unique identifier in AX.
        if ($account['VERECID']) {
            $contract = $contractRepository->findOneBy(['verecid' => $account['VERECID']]);
        }

        // If we can't find one, let's go ahead and create a new contract.
        if (!$contract) {
            $contract = new Contract();

            // These fields should only be set on new contracts.
            // They either won't change or they can be overridden after initial
            // import.
            $contract
                ->setBook($book)
                ->setProduct($product)
                ->setStartDate($startDate)
                ->setStartIssue($startDate->format('ym'))
                ->setOrderNumber($account['ORDERNO'])
                ->setMasterOrderNumber($this->valueOrNull($account['MASTERORDERNO']))
                ->setMasterOrderAccount($this->valueOrNull($account['MASTERORDERACCOUNTNO']))
                ->setLisfid($this->valueOrNull($account['LISFID']))
                ->setVerecid($this->valueOrNull($account['VERECID']));

            $this->entityManager->persist($contract);
        }

        // Now, let's update it.
        // These fields can not be overridden after initial import. Any changes
        // made outside of the importer will be wiped out on the next run.
        $contract
            ->setProperty($property)
            ->setRep($salesRep)
            ->setCollectionMessage($this->valueOrNull($account['COLLECTIONMESSAGE']))
            ->setEndDate($endDate->setTime(23, 59, 59))
            ->setEndIssue($endDate->format('ym'))
            ->setFeedStatus($account['STATUS'])
            ->setVeStatus((int)$account['VESTATUS'])
            ->setCurrentActive((($startDate <= $now) && ($endDate >= $now)) ? true : false)
            ->setColor($this->valueOrNull($account['COLOR']))
            ->setPosition($this->valueOrNull($account['PREMIUMPOSITION']))
            ->setEmailCopy((int)$account['EMAILADCOPY'])
            ->setFaxCopy((int)$account['FAXADCOPY'])
            ->setAutoRenewOption((int)$account['AUTORENEWOPTION']);

        if ($account['ITEMDEPARTMENT'] && $account['PREMIUMPOSITION']) {
            $contract->setSpaceReserved($account['ITEMDEPARTMENT'] . $account['PREMIUMPOSITION']);
        }

        return $contract;
    }
}
