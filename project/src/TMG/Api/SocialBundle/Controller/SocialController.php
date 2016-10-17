<?php

namespace TMG\Api\SocialBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use TMG\Api\ApiBundle\Controller\ApiController;
use TMG\Api\ApiBundle\Entity\Social;
use TMG\Api\ApiBundle\Exception as Exception;
use TMG\Api\SocialBundle\Handler\SocialHandler;
use TMG\Api\UtilityBundle\Annotations\Permissions;

use TMG\Api\ApiBundle\Entity\LocalEvent;
use TMG\Api\ApiBundle\Entity\Property;
use TMG\Api\ApiBundle\Entity\RateOurStayData;
use TMG\Api\ApiBundle\Entity\RateOurStaySubdomain;
use TMG\Api\ApiBundle\Entity\TripStayWinData;

use TMG\Api\SocialBundle\Handler\LocalEventHandler;
use TMG\Api\PropertiesBundle\Handler\PropertyHandler;
use TMG\Api\ReputationBundle\Handler\RateOurStaySubdomainHandler;
use TMG\Api\SocialBundle\Handler\TripStayWinDataHandler;

use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class Social Controller
 *
 * @Rest\NamePrefix("tmg_api_")
 * @package TMG\Api\SocialBundle\Controller
 */
class SocialController extends ApiController
{
    /**
     * @var LocalEvent
     */
    protected $localEventHandler;

    /**
     * @var PropertyHandler
     */
    protected $propertyHandler;

    /**
     * @var RateOurStaySubdomainHandler
     */
    private $rateOurStaySubdomainHandler;

    /**
     * @var SocialHandler
     */
    private $socialHandler;

    /**
     * @var TripStayWinDataHandler
     */
    protected $tripStayWinDataHandler;

    /**
     * @param LocalEventHandler $localEventHandler
     * @param PropertyHandler $propertyHandler
     * @param RateOurStaySubdomainHandler $rateOurStaySubdomainHandler
     * @param SocialHandler $socialHandler
     * @param TripStayWinDataHandler $tripStayWinDataHandler
     */
    public function __construct(
        LocalEventHandler $localEventHandler,
        PropertyHandler $propertyHandler,
        RateOurStaySubdomainHandler $rateOurStaySubdomainHandler,
        SocialHandler $socialHandler,
        TripStayWinDataHandler $tripStayWinDataHandler
    ) {
        $this->localEventHandler = $localEventHandler;
        $this->propertyHandler = $propertyHandler;
        $this->rateOurStaySubdomainHandler = $rateOurStaySubdomainHandler;
        $this->socialHandler = $socialHandler;
        $this->tripStayWinDataHandler = $tripStayWinDataHandler;
    }

    /**
     * @ApiDoc(
     *    section = "LocalEvent",
     *    resource = true,
     *    description = "Gets Local Event data based on parameters",
     *    statusCodes = {
     *        200 = "Returned when LocalEvent Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no LocalEvent data is found"
     *    }
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
     *      default="DESC",
     *      description="Used to determine sorting direction"
     * )
     *
     * @Rest\QueryParam(
     *      name="sortBy",
     *      default="createdAt",
     *      description="Determines value to sort by"
     * )
     *
     * @Rest\QueryParam(
     *      name="status",
     *      default=null,
     *      description="Status to filter by"
     * )
     *
     * @Rest\Get("/localevents")
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function getLocalEventsAction(ParamFetcher $paramFetcher)
    {
        $criteria = [];

        $params = $paramFetcher->all();
        foreach ($params as $key => $value) {
            switch ($key) {
                case 'page':
                case 'count':
                case 'order':
                case 'sortBy':
                    break;

                default:
                    if ($value != null) {
                        $criteria[$key] = $value;
                    }
                    break;
            }
        }

        $response = $this->localEventHandler->getRepository()->findBy(
            $criteria,
            [$paramFetcher->get('sortBy') => $paramFetcher->get('order')],
            $paramFetcher->get('count'),
            $paramFetcher->get('page')
        );

        return $response;
    }

    /**
     * @ApiDoc(
     *    section = "LocalEvent",
     *    resource = true,
     *    description = "Gets Local Event data based on parameters",
     *    statusCodes = {
     *        200 = "Returned when LocalEvent Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no LocalEvent data is found"
     *    }
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
     *      default="ASC",
     *      description="Used to determine sorting direction"
     * )
     *
     * @Rest\QueryParam(
     *      name="sortBy",
     *      default="scheduledAt",
     *      description="Determines value to sort by"
     * )
     *
     * @Rest\Get("/property/{propertyHash}/localevents")
     *
     * @param ParamFetcher $paramFetcher
     * @param string $propertyHash
     *
     * @return array
     */
    public function getLocalEventsByPropertyAction(ParamFetcher $paramFetcher, $propertyHash)
    {
        /** @var Property $property */
        $property = $this->propertyHandler->findOneBy(["hash" => $propertyHash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $propertyHash);

        $response = $this->localEventHandler->getRepository()->findBy(
            ["property" => $property],
            [$paramFetcher->get('sortBy') => $paramFetcher->get('order')],
            $paramFetcher->get('count'),
            $paramFetcher->get('page')
        );

        return $response;
    }

    /**
     * @ApiDoc(
     *    section = "LocalEvent",
     *    resource = true,
     *    description = "Gets a local event to property",
     *    statusCodes = {
     *        201 = "Returned when a Local Event has been created",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials"
     *    }
     * )
     *
     * @Rest\Get("/localevent/{eventHash}")
     *
     * @param $eventHash
     *
     * @return LocalEvent
     */
    public function getLocalEventAction($eventHash)
    {
        /** @var LocalEvent $localEvent */
        $localEvent = $this->localEventHandler->findOneBy(["hash" => $eventHash]);
        $this->checkResourceFound($localEvent, LocalEvent::NOT_FOUND_MESSAGE, $eventHash);

        return $localEvent;
    }

    /**
     * @ApiDoc(
     *    section = "LocalEvent",
     *    resource = true,
     *    description = "Adds a local event to property",
     *    statusCodes = {
     *        201 = "Returned when a Local Event has been created",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials"
     *    }
     * )
     *
     * @Rest\Post("/localevent/{propertyHash}")
     *
     * @Permissions({"post.local.event"})
     *
     * @param Request $request
     * @param $propertyHash
     *
     * @return LocalEvent
     */
    public function postLocalEventAction(Request $request, $propertyHash)
    {
        /** @var Property $property */
        $property = $this->propertyHandler->findOneBy(["hash" => $propertyHash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $propertyHash);

        $request->request->set('scheduled_at', new \DateTime($request->request->get('scheduled_at')));

        $parameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            LocalEvent::$requiredPostFields
        );

        $localEvent = $this->mapArrayToEntity(new LocalEvent(), $parameters);
        $localEvent->setProperty($property);

        return $this->localEventHandler->save($localEvent);
    }

    /**
     * @ApiDoc(
     *    section = "LocalEvent",
     *    resource = true,
     *    description = "Updates a local event",
     *    statusCodes = {
     *        201 = "Returned when a Local Event has been updated",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials"
     *    }
     * )
     *
     * @Rest\Put("/localevent/{eventHash}")
     *
     * @Permissions({"put.local.event"})
     *
     * @param Request $request
     * @param $eventHash
     *
     * @return LocalEvent
     */
    public function putLocalEventAction(Request $request, $eventHash)
    {
        $localEvent = $this->localEventHandler->findOneBy(["hash" => $eventHash]);
        $this->checkResourceFound($localEvent, LocalEvent::NOT_FOUND_MESSAGE, $eventHash);

        $request->request->set('scheduled_at', new \DateTime($request->request->get('scheduled_at')));

        $parameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            LocalEvent::$requiredPutFields
        );

        $this->mapArrayToEntity($localEvent, $parameters);

        return $this->localEventHandler->put($localEvent);
    }

    /**
     * @ApiDoc(
     *    section = "LocalEvent",
     *    resource = true,
     *    description = "Updates a local event",
     *    statusCodes = {
     *        201 = "Returned when a Local Event has been updated",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials"
     *    }
     * )
     *
     * @Rest\Patch("/localevent/{eventHash}")
     *
     * @Permissions({"patch.local.event"})
     *
     * @param Request $request
     * @param $eventHash
     *
     * @return LocalEvent
     */
    public function patchLocalEventAction(Request $request, $eventHash)
    {
        $localEvent = $this->localEventHandler->findOneBy(["hash" => $eventHash]);
        $this->checkResourceFound($localEvent, LocalEvent::NOT_FOUND_MESSAGE, $eventHash);

        if ($request->request->has('scheduled_at')) {
            $request->request->set('scheduled_at', new \DateTime($request->request->get('scheduled_at')));
        }

        $parameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            LocalEvent::$requiredPutFields,
            true
        );

        $this->mapArrayToEntity($localEvent, $parameters, [], true);

        return $this->localEventHandler->patch($localEvent);
    }

    /**
     * @ApiDoc(
     *    section = "LocalEvent",
     *    resource = true,
     *    description = "Updates a local event",
     *    statusCodes = {
     *        201 = "Returned when a Local Event has been updated",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials"
     *    }
     * )
     *
     * @Rest\Delete("/localevent/{eventHash}")
     *
     * @Permissions({"delete.local.event"})
     *
     * @param $eventHash
     *
     * @return LocalEvent
     */
    public function deleteLocalEventAction($eventHash)
    {
        /** @var LocalEvent $localEvent */
        $localEvent = $this->localEventHandler->findOneBy(["hash" => $eventHash]);
        $this->checkResourceFound($localEvent, LocalEvent::NOT_FOUND_MESSAGE, $eventHash);

        // Need to make sure user has not just permission to delete.

        return $this->localEventHandler->delete($localEvent->getId());
    }

    /**
     * @ApiDoc(
     *    section = "TripStayWin",
     *    resource = true,
     *    description = "Gets Trip Stay Win Data",
     *    statusCodes = {
     *        200 = "Returned when TripStayWin Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no TripStayWin data is found"
     *    }
     * )
     *
     * @Rest\Get("/tripstaywin/{propertyHash}")
     *
     * @param $propertyHash
     *
     * @return TripStayWinData
     */
    public function getTripStayWinAction($propertyHash)
    {
        $property = $this->propertyHandler->findOneBy(['hash' => $propertyHash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $propertyHash);

        $tripStayWin = $property->getTripStayWinData();

        if (!$tripStayWin) {
            $tripStayWin = new TripStayWinData();
        }

        return $tripStayWin;
    }

    /**
     * @ApiDoc(
     *    section = "TripStayWin",
     *    resource = true,
     *    description = "Gets Trip Stay Win Data",
     *    statusCodes = {
     *        200 = "Returned when TripStayWin Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no TripStayWin data is found"
     *    }
     * )
     *
     * @Rest\Get("/tripstaywin/getData/{subDomain}")
     *
     * @param $subDomain
     *
     * @return TripStayWinData
     */
    public function getTripStayWinBySubdomainAction($subDomain)
    {
        $resource = $this->rateOurStaySubdomainHandler->findOneBy(['subdomain' => $subDomain]);
        $this->checkResourceFound($resource, RateOurStaySubdomain::NOT_FOUND_MESSAGE, $subDomain);

        $property = $this->propertyHandler->findOneBy(['tripStayWinData' => $resource->getTripStayWinData()]);
        $this->checkResourceFound($property, RateOurStaySubdomain::NOT_FOUND_MESSAGE, $subDomain);

        $response = [];

        $response['property_info']['name'] = $property->getName();
        $response['property_info']['address'] = $property->getAddress();
        $response['tripStayWinData'] = $resource->getTripStayWinData();

        return $response;
    }

    /**
     * @ApiDoc(
     *    section = "TripStayWin",
     *    resource = true,
     *    description = "Adds subdomain to property",
     *    statusCodes = {
     *        201 = "Returned when a Property has been created",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials"
     *    }
     * )
     *
     * @Permissions({"post.trip.stay.win"})
     * @Rest\Post("/tripstaywin/{propertyHash}")
     *
     * @param Request $request
     * @param $propertyHash
     *
     * @return TripStayWinData
     */
    public function postTripStayWinAction(Request $request, $propertyHash)
    {
        /** @var Property $property */
        $property = $this->propertyHandler->findOneBy(['hash' => $propertyHash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $propertyHash);

        /** @var RateOurStayData $rateOurStay */
        $rateOurStay = $property->getRateOurStayData();

        $parameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            TripStayWinData::$requiredPostFields
        );

        // We will need to change this to a PUT and/or PATCH.  Dashboard isn't prepared for that yet.
        /** @var TripStayWinData $tripStayWinData */
        $tripStayWinData = $property->getTripStayWinData() ? $property->getTripStayWinData() : new TripStayWinData();

        $this->mapArrayToEntity($tripStayWinData, $parameters);

        if ($rateOurStay) {
            /** @var RateOurStaySubdomain $subdomain */
            foreach ($rateOurStay->getSubdomain() as $subdomain) {
                if (!array_key_exists($subdomain->getSubdomain(), $tripStayWinData->getSubDomainByName())) {
                    $tripStayWinData->setSubdomain($subdomain);
                }
            }
        } elseif (count($tripStayWinData->getSubdomain()) == 0 && !$request->request->has('subdomain')) {
            /** @var RateOurStaySubdomain $subdomain */
            $subdomain = new RateOurStaySubdomain();
            $subdomain->setSubdomain($property->getHash());
            $tripStayWinData->setSubdomain($subdomain);
            $subdomain->setTripStayWinData($tripStayWinData);
            $this->propertyHandler->save($subdomain);
        }

        if ($request->request->has('subdomain')) {
            $name = $request->request->get('subdomain');
            $subdomain = $this->rateOurStaySubdomainHandler->findOneBy(["subdomain" => $name]);

            $subdomain = $subdomain ? $subdomain : new RateOurStaySubdomain();

            if ($subdomain->getTripStayWinData() &&
                $subdomain->getTripStayWinData()->getId() != $tripStayWinData->getId()
            ) {
                // Throw error
                throw new Exception\BadRequestHttpException("Subdomain already exists for another property");
            }

            $subdomain->setSubdomain($name);
            $subdomain->setTripStayWinData($tripStayWinData);
            $this->rateOurStaySubdomainHandler->save($subdomain);
            $tripStayWinData->setSubdomain($subdomain);
        }

        $property->setTripStayWinData($tripStayWinData);
        $this->propertyHandler->save($property);

        return $tripStayWinData;
    }

    /**
     * @ApiDoc(
     *    section = "Social",
     *    resource = true,
     *    description = "Updates social",
     *    statusCodes = {
     *        201 = "Returned when Social has been updated",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials"
     *    }
     * )
     *
     * @Rest\Patch("/social/{propertyHash}")
     *
     * @Permissions({"patch.social"})
     *
     * @param Request $request
     * @param $propertyHash
     *
     * @return Social
     */
    public function patchSocialAction(Request $request, $propertyHash)
    {
        $property = $this->propertyHandler->findOneBy(['hash' => $propertyHash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $propertyHash);

        $social = $this->socialHandler->findOneBy(["property" => $property]);
        $this->checkResourceFound($social, Social::NOT_FOUND_MESSAGE, $propertyHash);

        $parameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            Social::$fillableFields,
            true
        );

        if ($request->request->has('multi_property_user') && $request->request->get('multi_property_user') == '') {
            $parameters['multi_property_user'] = '';
        }

        $this->mapArrayToEntity($social, $parameters, [], true);

        return $this->socialHandler->patch($social);
    }

    /**
     * @ApiDoc(
     *    section = "Social",
     *    resource = true,
     *    description = "Gets Social data",
     *    statusCodes = {
     *        200 = "Returned when Social data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no Social data is found"
     *    }
     * )
     *
     * @Rest\Get("/social/{propertyHash}")
     *
     * @param string $propertyHash
     *
     * @return Social
     */
    public function getSocialAction($propertyHash)
    {
        $property = $this->propertyHandler->findOneBy(['hash' => $propertyHash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $propertyHash);

        $social = $this->socialHandler->findOneBy(['property' => $property]);
        $this->checkResourceFound($social, Social::NOT_FOUND_MESSAGE, $propertyHash);

        return $social;
    }
}
