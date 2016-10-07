<?php

namespace TMG\Api\ReputationBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;
use TMG\Api\ApiBundle\Entity\Property;
use TMG\Api\ApiBundle\Entity\RateOurStayData;
use TMG\Api\ApiBundle\Entity\RateOurStaySubdomain;
use TMG\Api\ApiBundle\Entity\TripStayWinData;
use TMG\Api\PropertiesBundle\Handler\PropertyHandler;
use TMG\Api\ReputationBundle\Handler\RateOurStayDataHandler;
use TMG\Api\ReputationBundle\Handler\RateOurStaySubdomainHandler;
use TMG\Api\ApiBundle\Exception as Exception;

/**
 * Class RateOurStay Controller
 *
 * @Rest\NamePrefix("tmg_api_")
 * @package TMG\Api\ReputationBundle\Controller
 */
class RateOurStayController extends AbstractReputationController
{
    /**
     * @var RateOurStayDataHandler
     */
    private $rateOurStayDataHandler;

    /**
     * @var PropertyHandler
     */
    private $propertyHandler;

    /**
     * @var RateOurStaySubdomainHandler
     */
    private $rateOurStaySubdomainHandler;

    /**
     * @param RateOurStayDataHandler $rateOurStayDataHandler
     * @param PropertyHandler $propertyHandler
     */
    public function __construct(
        RateOurStayDataHandler $rateOurStayDataHandler,
        PropertyHandler $propertyHandler,
        RateOurStaySubdomainHandler $rateOurStaySubdomainHandler
    ) {
        $this->rateOurStayDataHandler = $rateOurStayDataHandler;
        $this->propertyHandler = $propertyHandler;
        $this->rateOurStaySubdomainHandler = $rateOurStaySubdomainHandler;
    }

    /**
     * @ApiDoc(
     *    section = "RateOurStay",
     *    resource = true,
     *    description = "Adds subdomain to property",
     *    statusCodes = {
     *        201 = "Returned when a Property has been created",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials"
     *    }
     * )
     *
     * @Rest\Post("/rateourstay/update/{propertyHash}")
     *
     * @param Request $request
     * @param $propertyHash
     *
     * @return Property
     */
    public function postSubDomainAction(Request $request, $propertyHash)
    {
        $property = $this->propertyHandler->findOneBy(['hash' => $propertyHash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $propertyHash);

        /** @var RateOurStayData $rateOurStayData */
        $rateOurStayData = $property->getRateOurStayData();
        $this->checkResourceFound($rateOurStayData, RateOurStaySubdomain::NOT_FOUND_MESSAGE, $propertyHash);

        $parameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            RateOurStaySubdomain::$requiredPostFields
        );

        /** @var TripStayWinData $tripStayWin */
        $tripStayWin = $property->getTripStayWinData();

        if ($tripStayWin) {
            /** @var RateOurStaySubdomain $subdomain */
            foreach ($tripStayWin->getSubdomain() as $subdomain) {
                if (!array_key_exists($subdomain->getSubdomain(), $rateOurStayData->getSubDomainByName())) {
                    $rateOurStayData->setSubdomain($subdomain);
                }
            }
        }

        $name = $request->request->get('subdomain');
        $subdomain = $this->rateOurStaySubdomainHandler->findOneBy(["subdomain" => $name]);

        $subdomain = $subdomain ? $subdomain : new RateOurStaySubdomain();
        $this->mapArrayToEntity($subdomain, $parameters);

        if ($subdomain->getRateOurStayData() &&
            $subdomain->getRateOurStayData()->getId() != $rateOurStayData->getId()
        ) {
            // Throw error
            throw new Exception\BadRequestHttpException("Subdomain already exists for another property");
        }

        $subdomain->setRateOurStayData($rateOurStayData);

        $this->propertyHandler->save($subdomain);

        return $subdomain;
    }

    /**
     * @ApiDoc(
     *    section = "RateOurStay",
     *    resource = true,
     *    description = "Gets rateOurStayData for a property property",
     *    statusCodes = {
     *        20 = "Returned when a RateOurStayData has found",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials"
     *    }
     * )
     *
     * @Rest\Get("/rateourstay/{propertyHash}")
     *
     * @param $propertyHash
     *
     * @return RateOurStayData
     */
    public function getRateOurStayAction($propertyHash)
    {
        /** @var Property $property */
        $property = $this->propertyHandler->findOneBy(['hash' => $propertyHash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $propertyHash);

        $rateOurStay = $property->getRateOurStayData();
        $this->checkResourceFound($rateOurStay, TripStayWinData::NOT_FOUND_MESSAGE, $propertyHash);

        return $rateOurStay;
    }

    /**
     * This is temporarily here.
     *
     * @Rest\Get("/rateourstay/getData/{subDomain}")
     *
     * @param string $subDomain
     * @return RateOurStayData
     */
    public function getDataAction($subDomain)
    {
        $resource = $this->rateOurStaySubdomainHandler->findOneBy(['subdomain' => $subDomain]);
        $this->checkResourceFound($resource, RateOurStaySubdomain::NOT_FOUND_MESSAGE, $subDomain);

        return $resource->getRateOurStayData();
    }
}
