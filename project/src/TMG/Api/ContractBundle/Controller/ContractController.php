<?php

namespace TMG\Api\ContractBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use TMG\Api\ApiBundle\Exception as Exception;
use TMG\Api\ContractBundle\Handler\ContractHandler;
use TMG\Api\ApiBundle\Entity\Contract;
use TMG\Api\ApiBundle\Entity\Repository\ContractRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use TMG\UtilitiesBundle\Exception\ApiProblemException;
use TMG\UtilitiesBundle\Problem\ApiProblem;
use TMG\UtilitiesBundle\Validators\DateValidator;

/**
 * Class ContractController
 *
 * @Rest\NamePrefix("tmg_api_")
 *
 * @package TMG\Api\ContractBundle\Controller
 */
class ContractController extends AbstractContractController
{
    /**
     * @var ContractHandler
     */
    private $contractHandler;

    /**
     * @var ContractRepository
     */
    private $contractRepository;

    /**
     * @param ContractHandler $contractHandler
     */
    public function __construct(ContractHandler $contractHandler)
    {
        $this->contractHandler = $contractHandler;

        $this->contractRepository = $this->contractHandler->getRepository();
    }

    /**
     * @ApiDoc(
     *    section = "contracts",
     *    resource = true,
     *    description = "Get Premium Position list from contracts.",
     *    statusCodes = {
     *        200 = "Returned when array is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no Premium Position data is found"
     *    }
     * )
     * @Rest\Get("/premiumpositionlist")
     */
    public function premiumPositionAction()
    {
        $premiumPositionList = $this->contractHandler->premiumPositionList();

        return $premiumPositionList;
    }

    /**
     * @ApiDoc(
     *    section = "contracts",
     *    resource = true,
     *    description = "Get a list of contracts active in the given date range.",
     *    statusCodes = {
     *        200 = "Returned when array is returned",
     *        400 = "Returned when bad JSON or required parameters are missing from the request.",
     *        401 = "Returned when the user is not logged in or has invalid credentials.",
     *        404 = "Returned when no contract data is found."
     *    }
     * )
     *
     * @Rest\QueryParam(
     *     name="count",
     *     requirements="\d+",
     *     default="50",
     *     description="Used to set the number of results per page."
     * )
     *
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     description="Used to select the page number"
     * )
     *
     * @Rest\QueryParam(
     *     name="startDate",
     *     description="Used to set the start date for the request."
     * )
     *
     * @Rest\QueryParam(
     *     name="endDate",
     *     description="Used to set the end date for the request."
     * )
     *
     * @Rest\Get("/contracts/active")
     *
     * If you don't set the "All" group, nothing will serialize.
     * This is a side effect of the paginated collection.
     * I'm desperately looking for a way around this.
     * @Rest\View(
     *     serializerGroups={"All", "feedback"}
     * )
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function getActiveContractListAction(ParamFetcher $paramFetcher)
    {
        $requestParams = $paramFetcher->all();

        if ($requestParams['startDate'] && $requestParams['endDate']) {
            $startDate = DateValidator::validate($requestParams['startDate'], 'Y-m-d');
            $endDate = DateValidator::validate($requestParams['endDate'], 'Y-m-d');

            if (!$startDate || !$endDate) {
                $apiProblem = new ApiProblem(400, ApiProblem::TYPE_VALIDATION_ERROR);

                throw new  ApiProblemException($apiProblem);
            }
        } else {
            $startDate = new \DateTime('first day of');

            $endDate = new \DateTime('last day of');
        }

        $startDate->setTime(00, 00, 00);
        $endDate->setTime(23, 59, 59);

        $queryBuilder = $this->contractHandler->getActiveContractsQueryBuilder(
            $startDate,
            $endDate
        );

        $routeParams = [
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'count' => $requestParams['count'],
        ];

        $paginatedCollection = $this
            ->get('tmg.pagination_factory')
            ->createCollection($queryBuilder, $requestParams, 'tmg_api_get_active_contract_list', $routeParams);

        $this->checkResourceFound(
            $paginatedCollection,
            'No active records found for date range',
            $startDate->format('Y-m-d') . ' - ' . $endDate->format('Y-m-d')
        );

        return $paginatedCollection;
    }
}
