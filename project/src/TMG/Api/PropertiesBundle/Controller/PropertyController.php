<?php
/**
 * PropertyController
 */
namespace TMG\Api\PropertiesBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use TMG\Api\ApiBundle\Entity\Address;
use TMG\Api\ApiBundle\Entity\Property;
use TMG\Api\LegacyBundle\Entity\CombinedListing;
use TMG\Api\LegacyBundle\Entity\Repository\CombinedListingRepository;

use /** @noinspection PhpUnusedAliasInspection */
    TMG\Api\UtilityBundle\Annotations\Permissions;
use /** @noinspection PhpUnusedAliasInspection */
    Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class PropertyController
 *
 * @Rest\NamePrefix("tmg_api_")
 */
class PropertyController extends AbstractPropertyController
{
    /**
     * @ApiDoc(
     *      section = "Properties",
     *      resource = true,
     *      description = "Returns Property data by Hash.",
     *      statusCodes = {
     *          200 = "Returned on success."
     *      }
     * )
     *
     * @Rest\Get("/checkproperty/{hash}")
     *
     * @param $hash
     * @return Property
     */
    public function checkPropertyAction($hash)
    {
        $property = $this->propertyHandler->checkPropertyByHash($hash);

        return $property;
    }

    /**
     * @ApiDoc(
     *      section = "Properties",
     *      resource = true,
     *      description = "This is a legacy search of all properties.  There are some
     *   practices in this controller that should be avoided.  Don't use for sample of what to do.",
     *      output="TMG\Api\PropertiesBundle\Entity\Property",
     *      statusCodes = {
     *          200 = "Returned on success."
     *      }
     * )
     *
     * @Rest\QueryParam(
     *      name = "sortListings",
     *      default = true,
     *      description = "This should always be true to use the legacy advanced sort,
     *          in which case it should never be passed in the query."
     * )
     *
     * @Rest\QueryParam(
     *      name = "radius",
     *      default = 25,
     *      description = "search radius"
     * )
     *
     * @Rest\QueryParam(
     *      name = "count",
     *      requirements = "\d+",
     *      default = 10,
     *      description = "Used to change the page item count"
     * )
     *
     * @Rest\QueryParam(
     *      name = "page",
     *      requirements = "\d+",
     *      default = 1,
     *      description = "Used to increment paging number. This is not 0 based, as would be usual, for legacy
     *          compatibility reasons."
     * )
     *
     * @Rest\QueryParam(
     *      name = "sortby",
     *      default = "distance",
     *      description = "Used to determine order. Normally we would use `order` for this, but we are matching this
     *          to a legacy entity. For the same reason, this key is all lowercase.
     *          Valid values are `activeAt`, `low-to-high`, `high-to-low`, `distance`.
     *          The default of activeAt"
     * )
     *
     * @Rest\QueryParam(
     *      name = "fromPrice",
     *      default = null,
     *      description = "search low price"
     * )
     *
     * * @Rest\QueryParam(
     *      name = "toPrice",
     *      default = null,
     *      description = "search high price"
     * )
     *
     * @Rest\QueryParam(
     *      name = "lat",
     *      default = null,
     *      description = "search latitude"
     * )
     *
     * @Rest\QueryParam(
     *      name = "long",
     *      default = null,
     *      description = "search longitude"
     * )
     *
     * @Rest\QueryParam(
     *      name = "name",
     *      default = null,
     *      description = "search property name"
     * )
     *
     * @Rest\QueryParam(
     *      name = "amenities",
     *      default = null,
     *      description = "amenities to search."
     * )
     *
     * @Rest\Get("/app/listing")
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function getListingsAction(ParamFetcher $paramFetcher)
    {
        $results = [];
        $lat = $paramFetcher->get('lat');
        $long = $paramFetcher->get('long');
        $radius = $paramFetcher->get('radius');
        $page = $paramFetcher->get('page') - 1;
        $max = $paramFetcher->get('count');

        $params = $paramFetcher->all();

        foreach ($params as $key => $value) {
            if ($value == null) {
                unset($params[$key]);
            }
        }

        /** @var CombinedListingRepository $repository */
        $repository = $this->legacyCombinedListingHandler->getRepository();

        $hotels = $repository->findListingsByCoords($lat, $long, $radius, $page, $max, $params);

        foreach ($hotels as $hotel) {
            $results[] = $this->propertyHandler->formatListing($hotel, $paramFetcher);
        }

        return [
            'page' => $page + 1,
            'count' => $max,
            'total' => $repository->findListingsCountByCoords($lat, $long, $radius, $params),
            'items' => $results,
        ];
    }

    /**
     * @ApiDoc(
     *      section = "Properties",
     *      resource = true,
     *      description = "This is a legacy search of all properties.  There are some
     *   practices in this controller that should be avoided.  Don't use for sample of what to do.",
     *      output="TMG\Api\PropertiesBundle\Entity\Property",
     *      statusCodes = {
     *          200 = "Returned on success."
     *      }
     * )
     **
     * @Rest\QueryParam(
     *      name = "lat",
     *      default = false,
     * )
     *
     * @Rest\QueryParam(
     *      name = "long",
     *      default = false,
     * )
     *
     * @Rest\Get("/app/listing/{propertyHash}")
     *
     * @param $paramFetcher
     * @param $propertyHash
     * @return array
     */
    public function getListingAction(ParamFetcher $paramFetcher, $propertyHash)
    {
        /** @var CombinedListing $combinedListing */
        $combinedListing = $this->legacyCombinedListingHandler->findOneBy(["id" => $propertyHash]);
        $this->checkResourceFound($combinedListing, Property::NOT_FOUND_MESSAGE, $propertyHash);

        return $this->propertyHandler->formatListing($combinedListing, $paramFetcher);
    }

    /**
     * @ApiDoc(
     *      section = "Properties",
     *      resource = true,
     *      description = "Returns array of properties",
     *      output="TMG\Api\PropertiesBundle\Entity\Property",
     *      statusCodes = {
     *          200 = "Returned on success."
     *      }
     * )
     * @Rest\QueryParam(
     *      name="count",
     *      requirements="\d+",
     *      default="50",
     *      description="Used to change the page item count"
     * )
     *
     * @Rest\QueryParam(
     *      name="page",
     *      requirements="\d+",
     *      default="0",
     *      description="Used to increment paging number"
     * )
     *
     * @Rest\QueryParam(
     *      name="order",
     *      default="name",
     *      description="Determines value to sort by."
     * )
     *
     * @Rest\QueryParam(
     *      name="sortBy",
     *      default="asc",
     *      description="Used to determine sorting direction"
     * )
     *
     * @Rest\Get("/properties")
     *
     * @param ParamFetcher $paramFetcher
     * @return array
     */
    public function getPropertiesAction(ParamFetcher $paramFetcher)
    {
        $pagingInfo = $this->getPagingInfo($paramFetcher);
        return $this->propertyHandler->getAllProperties([], $pagingInfo);
    }

    /**
     * @ApiDoc(
     *      section = "Properties",
     *      resource = true,
     *      description = "Returns Property by id.",
     *      output="TMG\Api\PropertiesBundle\Entity\Property",
     *      statusCodes = {
     *          200 = "Returned on success."
     *      }
     * )
     *
     * @Rest\Get("/property/{hash}")
     *
     * @param $hash
     * @return Property
     */
    public function getPropertyAction($hash)
    {
        $property = $this->propertyHandler->findOneBy(["hash" => $hash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $hash);

        return $property;
    }

    /**
     * @ApiDoc(
     *    section = "Properties",
     *    resource = true,
     *    description = "Creates Property",
     *    statusCodes = {
     *        201 = "Returned when a Property has been created",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials"
     *    }
     * )
     *
     * @Rest\Post("/property")
     *
     * @param Request $request
     * @return Rest\View
     */
    public function postPropertyAction(Request $request)
    {
        $property = new Property();
        $parameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            Property::$requiredPostFields
        );

        $this->mapArrayToEntity($property, $parameters);

        $this->validateAndMapRequestToParametersArray($parameters['address'], Address::$requiredPostFields);
        $address = new Address();
        $this->assembleAddress($parameters['address'], $address);

        $this->validateAndMapRequestToParametersArray($parameters['billing_address'], Address::$requiredPostFields);
        $billingAddress = new Address();
        $this->assembleAddress($parameters['billing_address'], $billingAddress);

        $this->assembleAmenities($parameters["amenities"], $property);

        $property->setAddress($address);
        $property->setBillingAddress($billingAddress);

        return $this->post($property, $this->propertyHandler);
    }

    /**
     * PUT single Property endpoint
     *
     * @ApiDoc(
     *      section = "Property",
     *      resource = true,
     *      description = "Updates Property",
     *      output="TMG\Api\PropertiesBundle\Entity\Property",
     *      statusCodes = {
     *          201 = "Returned when property is updated.",
     *          404 = "Returned when Property not found."
     *      },
     * )
     *
     * @Rest\Put("/property/{hash}")
     *
     * @param Request $request The Symfony Request object
     * @param string $hash The hash of the property to load
     *
     * @return Rest\View
     */
    public function putPropertyAction(Request $request, $hash)
    {
        $property = $this->propertyHandler->findOneBy(["hash" => $hash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $hash);

        $parameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            Property::$requiredPostFields
        );

        $this->mapArrayToEntity($property, $parameters);

        $this->validateAndMapRequestToParametersArray($parameters['address'], Address::$requiredPostFields);
        $address = $property->getAddress() ? $property->getAddress() : new Address();
        $this->assembleAddress($parameters['address'], $address);

        $this->validateAndMapRequestToParametersArray($parameters['billing_address'], Address::$requiredPostFields);
        $billing_address = $property->getBillingAddress() ? $property->getBillingAddress() : new Address();
        $this->assembleAddress($parameters['billing_address'], $billing_address);

        $this->assembleAmenities($parameters["amenities"], $property);

        $property->setAddress($address);
        $property->setBillingAddress($billing_address);

        return $this->put($property, $this->propertyHandler);
    }

    /**
     * @Rest\Patch("/property/{hash}")
     *
     * @param $request
     * @param $hash
     *
     * @return Rest\View
     */
    public function patchPropertyAction(Request $request, $hash)
    {
        $property = $this->propertyHandler->findOneBy(["hash" => $hash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $hash);

        $parameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            Property::$requiredPostFields,
            true
        );

        $this->mapArrayToEntity($property, $parameters, [], true);

        if (array_key_exists("address", $parameters) && $property->getAddress()) {
            $this->assembleAddress($parameters['address'], $property->getAddress(), true);
        }

        if (array_key_exists("billing_address", $parameters) && $property->getBillingAddress()) {
            $this->assembleAddress($parameters['billing_address'], $property->getBillingAddress(), true);
        }

        if (array_key_exists("amenities", $parameters)) {
            $this->assembleAmenities($parameters["amenities"], $property);
        }

        return $this->patch($property, $this->propertyHandler);
    }

    /**
     * @Rest\Delete("/property/{hash}")
     *
     * @param $hash
     *
     * @return Rest\View
     */
    public function deletePropertyAction($hash)
    {
        $property = $this->propertyHandler->findOneBy(["hash" => $hash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $hash);

        return $this->delete($property->getId(), $this->propertyHandler);
    }
}
