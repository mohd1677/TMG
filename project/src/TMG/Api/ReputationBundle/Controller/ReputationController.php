<?php

namespace TMG\Api\ReputationBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations as Rest;
use TMG\Api\ApiBundle\Entity\Property;
use TMG\Api\ApiBundle\Entity\Repository\ReputationSurveyRepository;
use TMG\Api\PropertiesBundle\Handler\PropertyHandler;
use TMG\Api\ReputationBundle\Handler\ReputationHandler;
use TMG\Api\ApiBundle\Entity\ReputationSurvey;
use TMG\Api\ReputationBundle\Handler\ReputationSurveyHandler;

use /** @noinspection PhpUnusedAliasInspection */
    Nelmio\ApiDocBundle\Annotation\ApiDoc;
use /** @noinspection PhpUnusedAliasInspection */
    FOS\RestBundle\Controller\Annotations\View;

/**
 * Class Reputation Controller
 *
 * @Rest\NamePrefix("tmg_api_")
 * @package TMG\Api\ReputationBundle\Controller
 */
class ReputationController extends AbstractReputationController
{
    /**
     * @var ReputationHandler
     */
    protected $reputationHandler;

    /**
     * @var PropertyHandler
     */
    protected $propertyHandler;

    /**
     * @var ReputationSurveyHandler
     */
    protected $reputationSurveyHandler;

    /**
     * @var ReputationSurveyRepository
     */
    protected $reputationSurveyRepository;

    /**
     * @param ReputationHandler $reputationHandler
     * @param PropertyHandler $propertyHandler
     * @param ReputationSurveyHandler $reputationSurveyHandler
     */
    public function __construct(
        ReputationHandler $reputationHandler,
        PropertyHandler $propertyHandler,
        ReputationSurveyHandler $reputationSurveyHandler
    ) {
        $this->reputationHandler = $reputationHandler;
        $this->propertyHandler = $propertyHandler;
        $this->reputationSurveyHandler = $reputationSurveyHandler;

        $this->reputationSurveyRepository = $this->reputationSurveyHandler->getRepository();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getReputationAction($id)
    {
        return $this->reputationHandler->getReputationById($id);
    }

    /**
     * @ApiDoc(
     *      section = "Reputation",
     *      resource = true,
     *      description = "Returns array of internal surveys",
     *      output="TMG\Api\ApiBundle\Entity\Property",
     *      statusCodes = {
     *          200 = "Returned on success."
     *      }
     * )
     *
     * @Rest\QueryParam(
     *      name="range",
     *      default="365",
     *      description="Used as range.  value 30 would be 30 days.",
     *      requirements="(30|60|90|180|365|all)+"
     * )
     *
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
     *      default="asc",
     *      description="Determines value to sort by."
     * )
     *
     * @Rest\QueryParam(
     *      name="sortBy",
     *      description="Used to determine sorting direction"
     * )
     *
     *
     * @Rest\Get("/reputation/surveys/{hash}")
     *
     * @param ParamFetcher $paramFetcher
     * @param $hash
     *
     * @return ReputationSurvey
     */
    public function getReputationInternalSurveyAction(ParamFetcher $paramFetcher, $hash)
    {
        $property = $this->propertyHandler->findOneBy(['hash' => $hash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $hash);

        $pagingInfo = $this->getPagingInfo($paramFetcher);

        return $this->reputationSurveyRepository->findPropertySurveys(
            $hash,
            $pagingInfo
        );
    }

    /**
     * ApiDoc(
     *      section = "Gets survey stats for a property",
     *      resource = true,
     *      description = "Returns array of internal surveys",
     *      output="TMG\Api\ApiBundle\Entity\Property",
     *      statusCodes = {
     *          200 = "Returned on success."
     *      }
     * )
     *
     * @Rest\QueryParam(
     *      name="range",
     *      default="365",
     *      description="Used as range.  value 30 would be 30 days.",
     *      requirements="(30|60|90|180|365|all)+"
     * )
     *
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
     *
     * @Rest\Get("/reputation/surveys/stats/{hash}")
     *
     * @param ParamFetcher $paramFetcher
     * @param $hash
     *
     * @return ReputationSurvey
     */
    public function getReputationInternalSurveyStatsAction(ParamFetcher $paramFetcher, $hash)
    {
        $property = $this->propertyHandler->findOneBy(['hash' => $hash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $hash);

        $pagingInfo = $this->getPagingInfo($paramFetcher);

        $data = new \stdClass();
        $yes = new \stdClass();
        $no = new \stdClass();
        $noSource = new \stdClass();
        $yesSource = new \stdClass();

        $data->recordsTotal = $this->reputationSurveyRepository->findCountBy(
            'id',
            $pagingInfo,
            ["hash" => $hash]
        );

        $yes->recordsTotal = $this->reputationSurveyRepository->findCountBy(
            'id',
            $pagingInfo,
            ["hash" => $hash, "yes" => 1]
        );

        $yesSource->email = $this->reputationSurveyRepository->findCountBy(
            'id',
            $pagingInfo,
            ["hash" => $hash, "source" => 1, "yes" => 1]
        );

        $yesSource->facebook = $this->reputationSurveyRepository->findCountBy(
            'id',
            $pagingInfo,
            ["hash" => $hash, "source" => 2, "yes" => 1]
        );

        $no->recordsTotal = $this->reputationSurveyRepository->findCountBy(
            'id',
            $pagingInfo,
            ["hash" => $hash, "no" => 1]
        );

        $noSource->email = $this->reputationSurveyRepository->findCountBy(
            'id',
            $pagingInfo,
            ["hash" => $hash, "source" => 1, "no" => 1]
        );

        $noSource->facebook = $this->reputationSurveyRepository->findCountBy(
            'id',
            $pagingInfo,
            ["hash" => $hash, "source" => 2, "no" => 1]
        );

        $no->source = (array) $noSource;
        $yes->source = (array) $yesSource;
        $data->yes = (array) $yes;
        $data->no = (array) $no;

        return (array) $data;

    }

    /**
     * ApiDoc(
     *      section = "Reputation",
     *      resource = true,
     *      description = "Returns array of internal surveys for data tables.  This is
     *  according to data tables documentation",
     *      output="TMG\Api\ApiBundle\Entity\Property",
     *      statusCodes = {
     *          200 = "Returned on success."
     *      }
     * )
     *
     * @Rest\QueryParam(
     *      name="range",
     *      default="365",
     *      description="Used as range.  value 30 would be 30 days.",
     *      requirements="(30|60|90|180|365|all)+"
     * )
     *
     * @Rest\QueryParam(
     *      name="length",
     *      requirements="\d+",
     *      default="50",
     *      description="Used to change the page item count"
     * )
     *
     * @Rest\QueryParam(
     *      name="draw",
     *      requirements="\d+",
     *      default="0",
     *      description="Used to determine draw paging number"
     * )
     *
     * @Rest\QueryParam(
     *      name="start",
     *      requirements="\d+",
     *      default="0",
     *      description="Used to increment paging number"
     * )
     *
     * @Rest\QueryParam(
     *      name="search",
     *      default="null",
     *      description="Used to determine sorting direction"
     * )
     *
     * @Rest\QueryParam(
     *      name="order",
     *      default="asc",
     *      description="Used to determine sorting direction"
     * )
     *
     * @Rest\QueryParam(
     *      name="columns",
     *      default="null",
     *      description="Used to determine sorting direction"
     * )
     *
     * @Rest\QueryParam(
     *      name="sortBy",
     *      default="rs.createdDate",
     *      description="Used to determine sorting direction"
     * )
     *
     *
     * @Rest\Get("/reputation/surveys/table/{hash}")
     *
     * @param ParamFetcher $paramFetcher
     * @param $hash
     *
     * @return mixed
     */
    public function getReputationInternalSurveyDataTableAction(ParamFetcher $paramFetcher, $hash)
    {
        $property = $this->propertyHandler->findOneBy(['hash' => $hash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $hash);

        $data = new \stdClass();
        $data->draw = $paramFetcher->get('draw');

        // We have to manually set some of these paging info params because data tables.js has weird
        // formatting that doesn't conform to our ways.
        $pagingInfo = $this->getPagingInfo($paramFetcher);
        $pagingInfo->setPage($paramFetcher->get("start"));
        $pagingInfo->setCount($paramFetcher->get("length"));

        if (!is_null($pagingInfo->getSearch())) {
            $pagingInfo->setSearch($pagingInfo->getSearch()['value']);
        }

        $columns = $paramFetcher->get("columns");

        if (!is_null($paramFetcher->get("order"))) {
            $order = $paramFetcher->get("order")[0];
            $pagingInfo->setSortBy($columns[$order["column"]]["data"]);
            $pagingInfo->setOrder($order["dir"]);

            if ($pagingInfo->getSortBy() == 'name') {
                $pagingInfo->setSortBy('lastName');
            } elseif ($pagingInfo->getSortBy() == 'source') {
                $pagingInfo->setSortBy('name');
            } elseif ($pagingInfo->getSortBy() == 'response_date') {
                $pagingInfo->setSortBy('responseDate');
            }
        }

        $surveys = $this->reputationSurveyRepository->findPropertySurveys(
            $hash,
            $pagingInfo
        );

        $data->recordsTotal = $this->reputationSurveyRepository->findPropertySurveyTotal(
            $hash,
            $pagingInfo
        );

        $data->recordsFiltered = $data->recordsTotal;

        $data->data = $this->reputationSurveyHandler->getDataTableResponse($surveys);

        return (array) $data;
    }
}
