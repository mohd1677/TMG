<?php
/**
 * PropertyController
 */
namespace TMG\Api\PropertiesBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use TMG\Api\ApiBundle\Controller\ApiController;
use FOS\RestBundle\Controller\Annotations as Rest;
use TMG\Api\ApiBundle\Entity\Address;
use TMG\Api\ApiBundle\Entity\Amenities;
use TMG\Api\ApiBundle\Entity\Property;
use TMG\Api\LegacyBundle\Formatting\LocationFormatter;
use TMG\Api\LegacyBundle\Handler\LegacyAddressHandler;
use TMG\Api\PropertiesBundle\Handler\AddressHandler;
use TMG\Api\PropertiesBundle\Handler\AmenitiesHandler;
use TMG\Api\PropertiesBundle\Handler\CountryHandler;
use TMG\Api\LegacyBundle\Handler\LegacyCombinedListingHandler;
use TMG\Api\PropertiesBundle\Handler\PostalCodeHandler;
use TMG\Api\PropertiesBundle\Handler\PropertyHandler;
use TMG\Api\PropertiesBundle\Handler\StateHandler;
use TMG\Api\ApiBundle\Entity\State;
use TMG\Api\ApiBundle\Entity\PostalCode;
use TMG\Api\ApiBundle\Entity\Country;

/**
 * Class Property Controller
 *
 * @Rest\NamePrefix("tmg_api_")
 */
abstract class AbstractPropertyController extends ApiController
{
    /** @var PropertyHandler  */
    protected $propertyHandler;

    /** @var AddressHandler  */
    protected $addressHandler;

    /** @var StateHandler  */
    protected $stateHandler;

    /** @var PostalCodeHandler  */
    protected $postalCodeHandler;

    /** @var CountryHandler  */
    protected $countryHandler;

    /** @var AmenitiesHandler */
    protected $amenitiesHandler;

    /** @var LegacyCombinedListingHandler  */
    protected $legacyCombinedListingHandler;

    /** @var  LocationFormatter */
    protected $locationFormatter;

    protected $legacyAddressHandler;

    /**
     * @param PropertyHandler $propertyHandler
     * @param AddressHandler $addressHandler
     * @param StateHandler $stateHandler
     * @param PostalCodeHandler $postalCodeHandler
     * @param CountryHandler $countryHandler
     * @param AmenitiesHandler $amenitiesHandler
     * @param LegacyCombinedListingHandler $legacyCombinedListingHandler
     * @param LocationFormatter $locationFormatter
     */
    public function __construct(
        PropertyHandler $propertyHandler,
        AddressHandler $addressHandler,
        StateHandler $stateHandler,
        PostalCodeHandler $postalCodeHandler,
        CountryHandler $countryHandler,
        AmenitiesHandler $amenitiesHandler,
        LegacyCombinedListingHandler $legacyCombinedListingHandler,
        LocationFormatter $locationFormatter,
        LegacyAddressHandler $legacyAddressHandler
    ) {
        $this->propertyHandler = $propertyHandler;
        $this->addressHandler = $addressHandler;
        $this->stateHandler = $stateHandler;
        $this->postalCodeHandler = $postalCodeHandler;
        $this->countryHandler = $countryHandler;
        $this->amenitiesHandler = $amenitiesHandler;
        $this->legacyCombinedListingHandler = $legacyCombinedListingHandler;
        $this->locationFormatter = $locationFormatter;
        $this->legacyAddressHandler = $legacyAddressHandler;
    }

    /**
     * Validates and maps amenities to Property
     *
     * @param array $parameters
     * @param Property $property
     */
    protected function assembleAmenities(array $parameters, Property $property)
    {
        $property->setAmenities(new ArrayCollection());

        foreach ($parameters as $id) {
            /** @var Amenities $amenity */
            $amenity = $this->amenitiesHandler->get($id);
            $this->checkResourceFound($amenity, Amenities::NOT_FOUND_MESSAGE, $id);
            $property->addAmenity($amenity);
        }
    }

    /**
     * Assembles a valid address to Address.
     *
     * @param $parameters
     * @param Address $address
     * @param $isPatch boolean
     */
    protected function assembleAddress($parameters, Address $address, $isPatch = false)
    {
        $parameters = $this->getMappedObjectToArray(
            $parameters,
            $this->stateHandler,
            "state",
            "name",
            State::NOT_FOUND_MESSAGE
        );

        $parameters = $this->getMappedObjectToArray(
            $parameters,
            $this->postalCodeHandler,
            "postal_code",
            "code",
            PostalCode::NOT_FOUND_MESSAGE
        );

        $parameters = $this->getMappedObjectToArray(
            $parameters,
            $this->countryHandler,
            "country",
            "name",
            Country::NOT_FOUND_MESSAGE
        );

        $this->mapArrayToEntity($address, $parameters, [], $isPatch);
    }
}
