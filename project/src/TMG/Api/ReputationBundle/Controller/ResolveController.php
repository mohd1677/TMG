<?php

namespace TMG\Api\ReputationBundle\Controller;

use DateTime;
use DateInterval;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use TMG\Api\ApiBundle\Controller\ApiController;

use TMG\Api\ApiBundle\Entity\ResolveContractorInvoice;
use TMG\Api\ContractBundle\Handler\ContractHandler;
use TMG\Api\ContractBundle\Handler\ProductsHandler;
use TMG\Api\UserBundle\Entity\User;
use TMG\Api\ApiBundle\Entity\Property;
use TMG\Api\ApiBundle\Entity\Reputation;
use TMG\Api\ApiBundle\Entity\ReputationReview;
use TMG\Api\ApiBundle\Entity\ReputationSite;
use TMG\Api\ApiBundle\Entity\ResolveResponse;
use TMG\Api\ApiBundle\Entity\ResolveResponseRating;
use TMG\Api\ApiBundle\Entity\ResolveSetting;
use TMG\Api\ApiBundle\Entity\ResolveSettingSite;
use TMG\Api\ApiBundle\Entity\ResolveTag;
use TMG\Api\ApiBundle\Entity\ResolveReviewTag;

use TMG\Api\PropertiesBundle\Handler\PropertyHandler;
use TMG\Api\ReputationBundle\Handler\ReputationEmailHandler;
use TMG\Api\ReputationBundle\Handler\ReputationHandler;
use TMG\Api\ReputationBundle\Handler\ReputationReviewHandler;
use TMG\Api\ReputationBundle\Handler\ReputationSiteHandler;
use TMG\Api\ReputationBundle\Handler\ResolveContractorInvoiceHandler;
use TMG\Api\ReputationBundle\Handler\ResolveResponseHandler;
use TMG\Api\ReputationBundle\Handler\ResolveResponseRatingHandler;
use TMG\Api\ReputationBundle\Handler\ResolveSettingHandler;
use TMG\Api\ReputationBundle\Handler\ResolveSettingSiteHandler;
use TMG\Api\ReputationBundle\Handler\ResolveReviewTagHandler;
use TMG\Api\ReputationBundle\Handler\ResolveTagHandler;
use TMG\Api\UserBundle\Handler\UserHandler;

use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations as Rest;

use /** @noinspection PhpUnusedAliasInspection */
    TMG\Api\UtilityBundle\Annotations\Permissions;
use /** @noinspection PhpUnusedAliasInspection */
    Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class Resolve Controller
 *
 * @Rest\NamePrefix("tmg_api_")
 * @package TMG\Api\ReputationBundle\Controller
 */
class ResolveController extends ApiController
{
    /**
     * @var ContractHandler
     */
    protected $contractHandler;

    /**
     * @var ProductsHandler
     */
    protected $productsHandler;

    /**
     * @var PropertyHandler
     */
    protected $propertyHandler;

    /**
     * @var ReputationEmailHandler
     */
    protected $reputationEmailHandler;

    /**
     * @var ReputationHandler
     */
    protected $reputationHandler;

    /**
     * @var ReputationReviewHandler
     */
    protected $reputationReviewHandler;

    /**
     * @var ReputationSiteHandler
     */
    protected $reputationSiteHandler;

    /**
     * @var ResolveContractorInvoiceHandler
     */
    protected $resolveContractorInvoiceHandler;

    /**
     * @var ResolveResponseHandler
     */
    protected $resolveResponseHandler;

    /**
     * @var ResolveResponseRatingHandler
     */
    protected $resolveResponseRatingHandler;

    /**
     * @var ResolveSettingHandler
     */
    protected $resolveSettingHandler;

    /**
     * @var ResolveSettingSiteHandler
     */
    protected $resolveSettingSiteHandler;

    /**
     * @var ResolveReviewTagHandler
     */
    protected $resolveReviewTagHandler;

    /**
     * @var ResolveTagHandler
     */
    protected $resolveTagHandler;

    /**
     * @var UserHandler
     */
    protected $userHandler;

    /**
     * @param ContractHandler $contractHandler
     * @param PropertyHandler $propertyHandler
     * @param ProductsHandler $productsHandler
     * @param ReputationEmailHandler $reputationEmailHandler
     * @param ReputationHandler $reputationHandler
     * @param ReputationReviewHandler $reputationReviewHandler
     * @param ReputationSiteHandler $reputationSiteHandler
     * @param ResolveContractorInvoiceHandler $resolveContractorInvoiceHandler
     * @param ResolveResponseHandler $resolveResponseHandler
     * @param ResolveResponseRatingHandler $resolveResponseRatingHandler
     * @param ResolveSettingHandler $resolveSettingHandler
     * @param ResolveSettingSiteHandler $resolveSettingSiteHandler
     * @param ResolveReviewTagHandler $resolveReviewTagHandler
     * @param ResolveTagHandler $resolveTagHandler
     * @param UserHandler $userHandler
     */
    public function __construct(
        ContractHandler $contractHandler,
        ProductsHandler $productsHandler,
        PropertyHandler $propertyHandler,
        ReputationEmailHandler $reputationEmailHandler,
        ReputationHandler $reputationHandler,
        ReputationReviewHandler $reputationReviewHandler,
        ReputationSiteHandler $reputationSiteHandler,
        ResolveContractorInvoiceHandler $resolveContractorInvoiceHandler,
        ResolveResponseHandler $resolveResponseHandler,
        ResolveResponseRatingHandler $resolveResponseRatingHandler,
        ResolveSettingHandler $resolveSettingHandler,
        ResolveSettingSiteHandler $resolveSettingSiteHandler,
        ResolveReviewTagHandler $resolveReviewTagHandler,
        ResolveTagHandler $resolveTagHandler,
        UserHandler $userHandler
    ) {
        $this->contractHandler = $contractHandler;
        $this->productsHandler = $productsHandler;
        $this->propertyHandler = $propertyHandler;
        $this->reputationEmailHandler = $reputationEmailHandler;
        $this->reputationHandler = $reputationHandler;
        $this->reputationReviewHandler = $reputationReviewHandler;
        $this->reputationSiteHandler = $reputationSiteHandler;
        $this->resolveResponseHandler = $resolveResponseHandler;
        $this->resolveContractorInvoiceHandler = $resolveContractorInvoiceHandler;
        $this->resolveResponseRatingHandler = $resolveResponseRatingHandler;
        $this->resolveSettingHandler = $resolveSettingHandler;
        $this->resolveSettingSiteHandler = $resolveSettingSiteHandler;
        $this->resolveReviewTagHandler = $resolveReviewTagHandler;
        $this->resolveTagHandler = $resolveTagHandler;
        $this->userHandler = $userHandler;
    }

    /**
     * @ApiDoc(
     *    section = "Resolve",
     *    resource = true,
     *    description = "Get a list of properties with current resolve contracts.",
     *    statusCodes = {
     *        200 = "Returned when array is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\Get("/resolve/properties")
     *
     * @Rest\View(serializerGroups={"resolve_property"})
     *
     */
    public function getResolvePropertiesAction()
    {
        $resolveProductCodes = $this->contractHandler->getResolveProductCodes();
        $resolveProducts = $this->productsHandler->getProducts($resolveProductCodes);
        $resolveContracts = $this->contractHandler->getActiveResolveContracts($resolveProducts);
        $resolveProperties = $this->propertyHandler->getActiveResolveProperties($resolveContracts);

        return $resolveProperties;
    }

    /**
     * @ApiDoc(
     *    section = "Resolve",
     *    resource = true,
     *    description = "Save Resolve Settings for a property",
     *    statusCodes = {
     *        200 = "Returned when ResolveResponse Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\Post("/resolve/setting/property/{propertyHash}")
     *
     * Permissions({"post.resolve.setting.property"})
     *
     * @param Request $request
     * @param $propertyHash
     *
     * @return ResolveSetting
     */
    public function postResolveSettingByPropertyAction(Request $request, $propertyHash)
    {
        /** @var Property $property */
        $property = $this->propertyHandler->findOneBy(['hash' => $propertyHash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $propertyHash);
        $this->checkResourceFound($property->getReputation(), Reputation::NOT_FOUND_MESSAGE_PROPERTY, $propertyHash);

        $parameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            ResolveSetting::$fillable
        );

        /** @var ResolveSetting $resolveSetting */
        $resolveSetting = $this->mapArrayToEntity(new ResolveSetting(), $parameters);
        $resolveSetting->setProperty($property);

        if ($request->get('email_analyst')) {
            $user = $this->userHandler->findOneBy(['emailCanonical' => $request->get('email_analyst')]);
            if ($user instanceof User) {
                $resolveSetting->setAnalyst($user);
            }
        }

        if ($request->get('email_hotelier')) {
            $user = $this->userHandler->findOneBy(['emailCanonical' => $request->get('email_hotelier')]);
            if ($user instanceof User) {
                $resolveSetting->setHotelier($user);
            }
        }

        foreach (ReputationSite::$resolveSites as $resolveSite) {
            /** @var ReputationSite $reputationSite */
            $reputationSite = $this->reputationSiteHandler->findOneBy(['id' => $resolveSite]);
            $this->checkResourceFound($reputationSite, ReputationSite::NOT_FOUND_MESSAGE, $resolveSite);

            if ($request->get('reputation_site_'.$reputationSite->getHash())) {
                $resolveSettingSite = new ResolveSettingSite();
                $resolveSettingSite->setReputationSite($reputationSite);
                $resolveSettingSite->setResolveSetting($resolveSetting);

                if ($request->get('effective_at_'.$reputationSite->getHash())) {
                    $effectiveAt = date_create($request->get('effective_at_'.$reputationSite->getHash()));
                    $resolveSettingSite->setEffectiveAt($effectiveAt);
                } else {
                    $effectiveAt = new DateTime(date('Y-m-01'));
                    $resolveSettingSite->setEffectiveAt($effectiveAt);
                }

                //set resolvable for this site for this property for this time frame
                $this->reputationReviewHandler->setResolvable(
                    $property->getReputation(),
                    $reputationSite,
                    true,
                    $effectiveAt
                );

                $this->resolveSettingSiteHandler->save($resolveSettingSite);
            }
        }

        return $this->resolveSettingHandler->save($resolveSetting);
    }

    /**
     * @ApiDoc(
     *    section = "Resolve",
     *    resource = true,
     *    description = "Update Resolve Settings for a property",
     *    statusCodes = {
     *        200 = "Returned when ResolveResponse Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\Put("/resolve/setting/property/{propertyHash}")
     *
     * Permissions({"put.resolve.setting.property"})
     *
     * @param Request $request
     * @param $propertyHash
     *
     * @return ResolveSetting
     */
    public function putResolveSettingByPropertyAction(Request $request, $propertyHash)
    {
        /** @var Property $property */
        $property = $this->propertyHandler->findOneBy(["hash" => $propertyHash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $propertyHash);

        $parameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            ResolveSetting::$fillable
        );

        /** @var ResolveSetting $resolveSetting */
        $resolveSetting = $property->getResolveSetting();
        $resolveSetting = $this->mapArrayToEntity($resolveSetting, $parameters);
        $resolveSetting->deleteAnalyst();
        $resolveSetting->deleteHotelier();
        $this->resolveSettingSiteHandler->deleteAllByResolveSetting($resolveSetting);

        if ($request->get('email_analyst')) {
            /** @var User $user */
            $user = $this->userHandler->findOneBy(['emailCanonical' => $request->get('email_analyst')]);
            if ($user instanceof User) {
                $resolveSetting->setAnalyst($user);
            }
        }

        if ($request->get('email_hotelier')) {
            /** @var User $user */
            $user = $this->userHandler->findOneBy(['emailCanonical' => $request->get('email_hotelier')]);
            if ($user instanceof User) {
                $resolveSetting->setHotelier($user);
            }
        }

        foreach (ReputationSite::$resolveSites as $resolveSite) {
            /** @var ReputationSite $reputationSite */
            $reputationSite = $this->reputationSiteHandler->findOneBy(['id' => $resolveSite]);
            $this->checkResourceFound($reputationSite, ReputationSite::NOT_FOUND_MESSAGE, $resolveSite);

            if ($request->get('reputation_site_'.$reputationSite->getHash())) {
                $resolveSettingSite = new ResolveSettingSite();
                $resolveSettingSite->setReputationSite($reputationSite);
                $resolveSettingSite->setResolveSetting($resolveSetting);

                //set all unresolvable for this site for this property as of the previous effectiveAt
                $this->reputationReviewHandler->setResolvable(
                    $property->getReputation(),
                    $reputationSite,
                    false,
                    $resolveSettingSite->getEffectiveAt()
                );

                if ($request->get('effective_at_'.$reputationSite->getHash())) {
                    $effectiveAt = date_create($request->get('effective_at_'.$reputationSite->getHash()));
                    $resolveSettingSite->setEffectiveAt($effectiveAt);
                } else {
                    $effectiveAt = new DateTime(date('Y-m-01'));
                    $resolveSettingSite->setEffectiveAt($effectiveAt);
                }

                //set resolvable for this site for this property for this time frame
                $this->reputationReviewHandler->setResolvable(
                    $property->getReputation(),
                    $reputationSite,
                    true,
                    $effectiveAt
                );

                $this->resolveSettingSiteHandler->save($resolveSettingSite);
            } else {
                //set all unresolvable for this site for this property
                $this->reputationReviewHandler->setResolvable($property->getReputation(), $reputationSite, false);
            }
        }

        return $this->resolveSettingHandler->save($resolveSetting);
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "Gets Resolve Tags",
     *    statusCodes = {
     *        200 = "Returned when ResolveTag Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
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
     *      default="tag",
     *      description="Determines value to sort by"
     * )
     *
     * @Rest\Get("/resolve/tag")
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function getResolveTagAction(ParamFetcher $paramFetcher)
    {
        $resolveTag = $this->resolveTagHandler->getRepository()->findBy(
            [],
            [$paramFetcher->get('sortBy') => $paramFetcher->get('order')]
        );

        $this->checkResourceFound($resolveTag, ResolveTag::NOT_FOUND_MESSAGE, null);

        return $resolveTag;
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "Gets allowed reputation sites for the resolve system",
     *    statusCodes = {
     *        200 = "Returned when data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no data is found"
     *    }
     * )
     *
     * @Rest\Get("/resolve/sites")
     *
     * @return array
     */
    public function getResolveSitesAction()
    {
        $return = [];

        foreach (ReputationSite::$resolveSites as $resolveSite) {
            /** @var ReputationSite $reputationSite */
            $reputationSite = $this->reputationSiteHandler->findOneBy(['id' => $resolveSite]);
            $this->checkResourceFound($reputationSite, ReputationSite::NOT_FOUND_MESSAGE, $resolveSite);
            $return[] = $reputationSite;
        }

        return $return;
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "Gets allowed reputation sites for a given property",
     *    statusCodes = {
     *        200 = "Returned when data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no data is found"
     *    }
     * )
     *
     * @Rest\Get("/resolve/reputation-sites/allowed/{propertyHash}")
     *
     * @param string $propertyHash
     * @return array
     */
    public function getAllowedReputationSitesByPropertyAction($propertyHash)
    {
        $return = [];

        $property = $this->propertyHandler->findOneBy(['hash' => $propertyHash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $propertyHash);

        $resolveSetting = $this->resolveSettingHandler->findOneBy(['property' => $property]);
        $this->checkResourceFound(
            $resolveSetting,
            ResolveSetting::NOT_FOUND_MESSAGE_PROPERTY,
            $propertyHash
        );

        $resolveSettingSites = $this->resolveSettingSiteHandler->getRepository()->findBy(
            ['resolveSetting' => $resolveSetting]
        );

        if ($resolveSettingSites) {
            /** @var ResolveSettingSite $site */
            foreach ($resolveSettingSites as $site) {
                $return[$site->getReputationSite()->getId()] = $site->getReputationSite()->getName();
            }
        }

        return $return;
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "Save Resolve Tags for a specific review",
     *    statusCodes = {
     *        200 = "Returned when ResolveTag Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\Post("/resolve/tag/{engageId}")
     *
     * @param Request $request
     * @param $engageId
     *
     * @return ArrayCollection
     */
    public function postResolveTagAction(Request $request, $engageId)
    {
        /** @var ReputationReview $reputationReview */
        $reputationReview = $this->reputationReviewHandler->findOneBy(['engageId' => $engageId]);
        $this->checkResourceFound($reputationReview, ReputationReview::NOT_FOUND_MESSAGE_ENGAGE_ID, $engageId);

        foreach ($request->request->all() as $tagHash => $tagValue) {
            /** @var ResolveTag $resolveTag */
            $resolveTag = $this->resolveTagHandler->findOneBy(['hash' => $tagHash]);
            $this->checkResourceFound($resolveTag, ResolveTag::NOT_FOUND_MESSAGE_HASH, $tagHash);

            /** @var ResolveReviewTag $resolveReviewTag */
            $resolveReviewTag = $this->resolveReviewTagHandler->findOneBy(
                ['resolveTag' => $resolveTag, 'reputationReview' => $reputationReview]
            );
            if (false === $resolveReviewTag instanceof ResolveReviewTag) {
                $resolveReviewTag = new ResolveReviewTag();
            }
            $resolveReviewTag->setResolveTag($resolveTag);
            $resolveReviewTag->setValue($tagValue);
            $resolveReviewTag->setReputationReview($reputationReview);
            $resolveReviewTag->getReputationReview()->addResolveReviewTag($resolveReviewTag);
            $resolveReviewTag->getReputationReview()->setTaggedAt(new DateTime());
            $this->resolveReviewTagHandler->save($resolveReviewTag);
        }

        return $reputationReview->getResolveReviewTag();
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "Save Resolve Response for a specific review",
     *    statusCodes = {
     *        200 = "Returned when ResolveTag Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\Post("/resolve/pending/{engageId}")
     *
     * @param Request $request
     * @param $engageId
     *
     * @return array
     */
    public function postResolvePendingAction(Request $request, $engageId)
    {
        $return = [];

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $resolveResponseParameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            ResolveResponse::$fillable
        );

        /** @var ReputationReview $reputationReview */
        $reputationReview = $this->reputationReviewHandler->findOneBy(['engageId' => $engageId]);
        $this->checkResourceFound($reputationReview, ReputationReview::NOT_FOUND_MESSAGE_ENGAGE_ID, $engageId);

        /** @var ResolveResponse $resolveResponse */
        $resolveResponse = $this->mapArrayToEntity(new ResolveResponse(), $resolveResponseParameters);
        $resolveResponse->setReputationReview($reputationReview);
        $resolveResponse->setUser($user);

        switch ($resolveResponseParameters['action']) {
            case 'response':
                // if no response was previously logged
                if ($reputationReview->getRespondedAt() == null) {
                    // if this post includes contractor rating/feedback
                    if ($request->request->get('rating') || $request->request->get('feedback')) {
                        $resolveResponseRatingParameters = $this->validateAndMapRequestToParametersArray(
                            $request->request->all(),
                            ResolveResponseRating::$fillable
                        );
                        /** @var ResolveResponseRating $resolveResponseRating */
                        $resolveResponseRating = $this->mapArrayToEntity(
                            new ResolveResponseRating(),
                            $resolveResponseRatingParameters
                        );
                        $resolveResponseRating->setResolveResponse($resolveResponse);
                        $resolveResponseRating->setRatedBy($user);
                        $resolveResponseRating->setProposedBy($reputationReview->getReservedBy());
                        $resolveResponseRating->setPaymentValue(
                            $this->resolveResponseRatingHandler->calculatePaymentValue(
                                $resolveResponseRating->getProposedBy(),
                                $resolveResponseRating->getRating()
                            )
                        );

                        $this->resolveResponseRatingHandler->save($resolveResponseRating);
                    }
                    $reputationReview->setRespondedAt(new DateTime());

                    $return = $this->resolveResponseHandler->save($resolveResponse);
                }
                break;

            case 'approve':
                $reputationReview->setApprovedAt(new DateTime($request->request->get('approved_at')));

                if ($request->request->get('critical') === 1) {
                    $reputationReview->setCritical(true);
                } else {
                    $reputationReview->setCritical(false);
                }

                $return = $this->resolveResponseHandler->save($resolveResponse);
                break;

            case 'resolve':
                if ($reputationReview->getResolvedAt() == null) {
                    $reputationReview->setResolvedAt(new DateTime());
                    $return = $this->resolveResponseHandler->save($resolveResponse);
                }
                break;

            case 'propose':
                if ($reputationReview->getReservedBy() == $user) {
                    if ($reputationReview->getProposedAt() == null) {
                        $reputationReview->setProposedAt(new DateTime());
                        $this->resolveResponseHandler->save($resolveResponse);

                        return [
                            'status' => 'success',
                            'reason' => 'Response submitted.',
                        ];
                    } else {
                        return [
                            'status' => 'error',
                            'reason' => 'A response has already been proposed.',
                        ];
                    }
                } else {
                    return [
                        'status' => 'error',
                        'reason' => 'Sorry, this review is not yours.',
                    ];
                }
                break;

            default:
                // Unexpected value, don't do anything.
                break;
        }

        return $return;
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "Anonymous quick-approval link from the hotelier email",
     *    statusCodes = {
     *        200 = "Returned when valid data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\Post("/resolve/quick-approval")
     *
     * @param Request $request
     *
     * @return array
     */
    public function postQuickApprovalAction(Request $request)
    {
        $return = [];
        $parameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            ResolveResponse::$fillable
        );

        /** @var ReputationReview $reputationReview */
        $reputationReview = $this->reputationReviewHandler->findOneBy(
            ['engageId' => $request->request->get('engage_id')]
        );
        $this->checkResourceFound(
            $reputationReview,
            ReputationReview::NOT_FOUND_MESSAGE_ENGAGE_ID,
            $request->request->get('engage_id')
        );

        /** @var ResolveResponse $resolveResponse */
        $resolveResponse = $this->mapArrayToEntity(new ResolveResponse(), $parameters);
        $resolveResponse->setReputationReview($reputationReview);
        $resolveResponse->setUser(
            $reputationReview->getReputation()->getProperty()->getResolveSetting()->getHotelier()
        );

        switch ($parameters['action']) {
            case 'approve':
                $reputationReview->setApprovedAt(new DateTime($request->request->get('approved_at')));

                if ($request->request->get('critical') === 1) {
                    $reputationReview->setCritical(true);
                } else {
                    $reputationReview->setCritical(false);
                }

                $return = $this->resolveResponseHandler->save($resolveResponse);
                break;

            default:
                // Unexpected value, don't do anything.
                break;
        }

        return $return;
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "Gets reviews assigned to a property for a certain month",
     *    statusCodes = {
     *        200 = "Returned when ResolveResponse Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\QueryParam(
     *      name="start",
     *      description="start date"
     * )
     *
     * @Rest\QueryParam(
     *      name="end",
     *      description="end date"
     * )
     *
     * @Rest\QueryParam(
     *      name="pending",
     *      description="check for specific pending status"
     * )
     *
     * @Rest\Get("/resolve/review/property/{propertyHash}")
     *
     * @Rest\View(serializerGroups={"review_detail"})
     *
     * @param ParamFetcher $paramFetcher
     * @param $propertyHash
     *
     * @return array
     * @throws \exception
     */
    public function getReviewsByPropertyAction(ParamFetcher $paramFetcher, $propertyHash)
    {
        $params = $paramFetcher->all();
        $launch = new DateTime(ResolveResponse::LAUNCH_DATE);
        $today = new DateTime();

        $interval = $launch->diff(new DateTime($params['start']));
        $start = $interval->invert ? $launch : new DateTime($params['start']);
        $start->setTime(0, 0, 0);

        $interval = $today->diff(new DateTime($params['end']));
        $end = !$interval->invert ? $today : new DateTime($params['end']);
        $end->setTime(23, 59, 59);

        /** @var Property $property */
        $property = $this->propertyHandler->findOneBy(['hash' => $propertyHash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $propertyHash);

        /** @var Reputation $reputation */
        $reputation = $this->reputationHandler->getRepository()->findOneBy(['property' => $property]);
        $this->checkResourceFound($reputation, Reputation::NOT_FOUND_MESSAGE_PROPERTY, $property->getHash());

        $sites = $this->resolveSettingSiteHandler->getSitesByResolveSetting($property->getResolveSetting());

        $this->reputationReviewHandler->clearResponseReservations();

        switch ($params['pending']) {
            case 'approval':
            case 'resolve':
                $reviews = $this->reputationReviewHandler->getPendingResponseByReputation(
                    $params['pending'],
                    $reputation,
                    $launch,
                    $today,
                    $sites
                );
                break;
            default:
                $reviews = $this->reputationReviewHandler->getPendingResponseByReputation(
                    $params['pending'],
                    $reputation,
                    $start,
                    $end,
                    $sites
                );
                break;
        }

        return $reviews;
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "Gets reviews assigned to a property for a certain tag",
     *    statusCodes = {
     *        200 = "Returned when ResolveResponse Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\QueryParam(
     *      name="site",
     *      requirements="\d+",
     *      default=null,
     *      description="review site to work with, defaults to trip advisor"
     * )
     *
     * @Rest\QueryParam(
     *      name="tag",
     *      description="tag whose data you want to retrieve"
     * )
     *
     * @Rest\Get("/resolve/review/tag/stats/property/{propertyHash}")
     *
     * @param ParamFetcher $paramFetcher
     * @param $propertyHash
     *
     * @return array
     */
    public function getTagReviewsAndStatsByPropertyAction(ParamFetcher $paramFetcher, $propertyHash)
    {
        $data = [
            'reviews' => [],
            '1_star' => 0,
            '2_star' => 0,
            '3_star' => 0,
            '4_star' => 0,
            '5_star' => 0,
        ];
        $params = $paramFetcher->all();
        $start = new DateTime(ResolveResponse::LAUNCH_DATE);
        $end = new DateTime();
        $tagValue = 0;
        $reviewValue = 0;
        $reviewsMissingTag = [];
        $lastMentioned = 'n/a';
        $site = null;

        /** @var Property $property */
        $property = $this->propertyHandler->findOneBy(['hash' => $propertyHash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $propertyHash);

        if ($params['site']) {
            /** @var ReputationSite $site */
            $site = $this->reputationSiteHandler->getRepository()->findOneBy(['id' => $params['site']]);
            $this->checkResourceFound($site, ReputationSite::NOT_FOUND_MESSAGE, $params['site']);
        }

        /** @var Reputation $reputation */
        $reputation = $this->reputationHandler->getRepository()->findOneBy(['property' => $property]);
        $this->checkResourceFound($reputation, Reputation::NOT_FOUND_MESSAGE_PROPERTY, $property->getHash());

        $reviews = $this->reputationReviewHandler->getCompletedForAnalyst(
            $reputation,
            $site,
            null,
            $start,
            $end
        );

        /** @var ReputationReview $review */
        foreach ($reviews as $review) {
            $tags = $this->resolveReviewTagHandler->stringifyReviewTagIndices($review->getResolveReviewTag());
            $hasTag = false;
            $soughtTag = null;
            $cleanTags = [];
            foreach ($tags as $key => $tag) {
                if ($tag['tag'] == $params['tag'] && $tag['value'] > 0) {
                    $hasTag = true;
                    $soughtTag = $tag;
                    $cleanTags[] = $tag['tag'];
                } elseif ($tag['value'] == 0) {
                    unset($tags[$key]);
                } else {
                    $cleanTags[] = $tag['tag'];
                }
            }

            if ($hasTag) {
                $data['reviews'][] = [
                    'site_name' => $review->getSite()->getName(),
                    'post_date' => $review->getPostDate()->format('m/d/Y'),
                    'property_name' => $property->getName(),
                    'comment' => $review->getContent(),
                    'rating' => $review->getTone(),
                    'property_hash' => $property->getHash(),
                    'tag' => $soughtTag,
                    'tags' => $cleanTags,
                    'username' => $review->getUsername(),
                ];

                $tagValue += $soughtTag['value'];
                $reviewValue += (int)$review->getTone();

                if ($lastMentioned == 'n/a') {
                    $lastMentioned = $review->getPostDate();
                } elseif ($review->getPostDate() > $lastMentioned) {
                    $lastMentioned = $review->getPostDate();
                }

                $tone = (int)$review->getTone();

                switch ($tone) {
                    case 1:
                        $data['1_star']++;
                        break;
                    case 2:
                        $data['2_star']++;
                        break;
                    case 3:
                        $data['3_star']++;
                        break;
                    case 4:
                        $data['4_star']++;
                        break;
                    case 5:
                        $data['5_star']++;
                        break;
                    default:
                        // Unexpected value, don't do anything.
                        break;
                }
            } else {
                $reviewsMissingTag[] = $review;
            }
        }

        $totalReviews = count($data['reviews']) + count($reviewsMissingTag);
        if ($totalReviews > 0) {
            $data['percent_reviews_present'] = (float)(count($data['reviews']) / $totalReviews) * 100;
        } else {
            $data['percent_reviews_present'] = 0;
        }

        if (count($data['reviews']) > 0) {
            $data['tag_average'] = (float)($tagValue / count($data['reviews']));
            $data['review_average'] = (float)($reviewValue / count($data['reviews']));
        } else {
            $data['tag_average'] = 0;
            $data['review_average'] = 0;
        }

        if ($lastMentioned != 'n/a') {
            $data['last_mentioned'] = $lastMentioned->format('m/d/Y');
        } else {
            $data['last_mentioned'] = $lastMentioned;
        }

        return $data;
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "Gets reviews assigned to a property for a certain month",
     *    statusCodes = {
     *        200 = "Returned when ResolveResponse Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\QueryParam(
     *      name="start",
     *      description="start date"
     * )
     *
     * @Rest\QueryParam(
     *      name="end",
     *      description="end date"
     * )
     *
     * @Rest\QueryParam(
     *      name="status",
     *      description="get results for a specific review status"
     * )
     *
     * @Rest\QueryParam(
     *      name="property_hash",
     *      description="get results for a specific property"
     * )
     *
     * @Rest\QueryParam(
     *      name="current_sites",
     *      description="only get results for current resolve settings"
     * )
     *
     * @Rest\QueryParam(
     *      name="sites",
     *      description="get results for specific site(s) (one or more comma separated); overrides current_sites"
     * )
     *
     * @Rest\Get("/resolve/reviews/{role}")
     *
     * @Rest\View(serializerGroups={"review_detail"})
     *
     * @param ParamFetcher $paramFetcher
     * @param $role
     *
     * @return array
     */
    public function getReviewsByRoleAction(ParamFetcher $paramFetcher, $role)
    {
        $reviews = [];
        $params = $paramFetcher->all();
        $launch = new DateTime(ResolveResponse::LAUNCH_DATE);
        $today = new DateTime();

        $interval = $launch->diff(new DateTime($params['start']));
        $start = $interval->invert || empty($params['start']) ? $launch : new DateTime($params['start']);
        $start->setTime(0, 0, 0);

        $interval = $today->diff(new DateTime($params['end']));
        $end = !$interval->invert || empty($params['end']) ? $today : new DateTime($params['end']);
        $end->setTime(23, 59, 59);

        $this->reputationReviewHandler->clearResponseReservations();

        switch ($role) {
            case 'contractor':
                $user = $this->container->get('security.token_storage')->getToken()->getUser();

                switch ($params['status']) {
                    case 'proposed':
                        $reviews = $this->reputationReviewHandler->getProposed(
                            null,
                            null,
                            $user,
                            $start,
                            $end
                        );
                        break;

                    case 'unpaid':
                        $reviews = $this->reputationReviewHandler->getUnpaid(
                            null,
                            null,
                            $user,
                            $start,
                            $end
                        );
                        break;

                    default:
                        // Unexpected value, don't do anything.
                        break;
                }
                break;

            case 'analyst':
                $reputation = null;
                $sites = [];

                if ($params['property_hash']) {
                    /** @var Property $property */
                    $property = $this->propertyHandler->findOneBy(['hash' => $params['property_hash']]);
                    $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $params['property_hash']);
                    $reputation = $property->getReputation();
                    $this->checkResourceFound(
                        $reputation,
                        Reputation::NOT_FOUND_MESSAGE_PROPERTY,
                        $params['property_hash']
                    );
                    $this->checkResourceFound(
                        $property->getResolveSetting(),
                        ResolveSetting::NOT_FOUND_MESSAGE_PROPERTY,
                        $params['property_hash']
                    );

                    if ($params['sites']) {
                        $sites = explode(',', $params['sites']);
                    } elseif ($params['current_sites']) {
                        $resolveSettingSites = $property->getResolveSetting()->getResolveSettingSites();
                        /** @var ResolveSettingSite $resolveSettingSite */
                        foreach ($resolveSettingSites as $resolveSettingSite) {
                            $sites[] = $resolveSettingSite->getReputationSite();
                        }
                    }
                }

                switch ($params['status']) {
                    case 'proposed':
                        $reviews = $this->reputationReviewHandler->getProposed(
                            null,
                            null,
                            null,
                            $start,
                            $end
                        );
                        break;

                    case 'response':
                        $reviews = $this->reputationReviewHandler->getPendingResponse(
                            $reputation,
                            $sites,
                            null,
                            $start,
                            $end
                        );
                        break;

                    case 'approval':
                        $reviews = $this->reputationReviewHandler->getPendingApproval(
                            $reputation,
                            $sites,
                            null,
                            $start,
                            $end
                        );
                        break;

                    case 'resolve':
                        $reviews = $this->reputationReviewHandler->getPendingResolve(
                            $reputation,
                            $sites,
                            null,
                            $start,
                            $end
                        );
                        break;

                    case 'completed':
                        $reviews = $this->reputationReviewHandler->getCompletedForAnalyst(
                            $reputation,
                            $sites,
                            null,
                            $start,
                            $end
                        );
                        break;

                    default:
                        // Unexpected value, don't do anything.
                        break;
                }
                break;

            default:
                // Unexpected value, don't do anything.
                break;
        }

        return $reviews;
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "Gets reviews assigned to a property for a certain month",
     *    statusCodes = {
     *        200 = "Returned when ResolveResponse Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\QueryParam(
     *      name="start",
     *      description="start date"
     * )
     *
     * @Rest\QueryParam(
     *      name="site",
     *      requirements="\d+",
     *      default="18",
     *      description="review site to work with, defaults to trip advisor"
     * )
     *
     * @Rest\Get("/resolve/review/tag/property/{propertyHash}")
     *
     * @param ParamFetcher $paramFetcher
     * @param $propertyHash
     *
     * @return array
     * @throws \exception
     */
    public function getTagsTrendByPropertyAction(ParamFetcher $paramFetcher, $propertyHash)
    {
        $data = [];
        $params = $paramFetcher->all();

        //set start to 1st of month
        $start = new DateTime();
        $start = DateTime::createFromFormat('Y-m-d', $start->format('Y-m-01'));
        $start->setTime(0, 0, 0);

        $currentMonth = $start->sub(new DateInterval('P5M'));
        $currentMonth->setTime(0, 0, 0);

        $end = DateTime::createFromFormat('Y-m-d', $currentMonth->format('Y-m-t'));
        $end->setTime(23, 59, 59);

        /** @var Property $property */
        $property = $this->propertyHandler->findOneBy(['hash' => $propertyHash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $propertyHash);

        /** @var ReputationSite $reputationSite */
        $reputationSite = $this->reputationSiteHandler->getRepository()->findOneBy(['id' => $params['site']]);
        $this->checkResourceFound($reputationSite, ReputationSite::NOT_FOUND_MESSAGE, $params['site']);

        /** @var Reputation $reputation */
        $reputation = $this->reputationHandler->getRepository()->findOneBy(['property' => $property]);
        $this->checkResourceFound($reputation, Reputation::NOT_FOUND_MESSAGE_PROPERTY, $property->getHash());

        /** @var array $resolveTag */
        $resolveTag = $this->resolveTagHandler->getRepository()->findBy(
            [],
            ['tag' => 'ASC']
        );
        $this->checkResourceFound($resolveTag, ResolveTag::NOT_FOUND_MESSAGE, null);

        $sites = $this->resolveSettingSiteHandler->getSitesByResolveSetting($property->getResolveSetting());

        $resolveTags = [];
        /** @var ResolveTag $tag */
        foreach ($resolveTag as $tag) {
            $resolveTags[] = $tag->getTag();
        }

        //initialize data array
        /** @var string $tag */
        foreach ($resolveTags as $tag) {
            $data[$tag][0] = [
                'tag' => $tag,
                'count' => 0,
                'value' => 0,
                'average' => 0,
                'date' => $currentMonth->format('Y-m'),
            ];
        }

        //get last six months of tag data
        for ($i = 0; $i < 6; $i++) {
            $reviews = $this->reputationReviewHandler->getPendingResponseByReputation(
                'completed',
                $reputation,
                $currentMonth,
                $end,
                $sites
            );

            //initialize data array next iteration
            if ($i > 0) {
                foreach ($data as $key => $datum) {
                    $data[$key][$i] = [
                        'tag' => $key,
                        'count' => $data[$key][$i - 1]['count'],
                        'value' => $data[$key][$i - 1]['value'],
                        'average' => $data[$key][$i - 1]['average'],
                        'date' => $currentMonth->format('Y-m'),
                    ];
                }
            }

            if (count($reviews) > 0) {
                /** @var ReputationReview $v */
                foreach ($reviews as $v) {
                    $tags = $this->resolveReviewTagHandler->stringifyReviewTagIndices($v->getResolveReviewTag());
                    foreach ($tags as $tag) {
                        if (array_key_exists('value', $tag) && $tag['value'] > 0) {
                            $data[$tag['tag']][$i]['count']++;
                            $data[$tag['tag']][$i]['value'] += $tag['value'];
                            $data[$tag['tag']][$i]['average'] =
                                (float)($data[$tag['tag']][$i]['value'] / $data[$tag['tag']][$i]['count']);
                        }
                    }
                }
            }

            $currentMonth = $currentMonth->add(new DateInterval('P1M'));
            $currentMonth->setTime(0, 0, 0);
            $end = DateTime::createFromFormat('Y-m-d', $currentMonth->format('Y-m-t'));
            $end->setTime(23, 59, 59);
        }

        return $data;
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "Gets the next eligible review for a contractor",
     *    statusCodes = {
     *        200 = "Returned when expected data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\QueryParam(
     *      name="engageId",
     *      description="engage id to request a specific review"
     * )
     *
     * @Rest\QueryParam(
     *      name="site",
     *      requirements="\d+",
     *      description="review site to work with"
     * )
     *
     * @Rest\View(serializerGroups={"review_detail"})
     *
     * @Rest\Get("/resolve/review/queue/response")
     *
     * @Permissions({"get.resolve.review.queue.response"})
     *
     * @param ParamFetcher $paramFetcher
     * @return null|ReputationReview
     */
    public function getReviewForResponseAction(ParamFetcher $paramFetcher)
    {
        $reputationSite = null;
        $site = $paramFetcher->get('site');
        $engageId = $paramFetcher->get('engageId');

        if ($site) {
            /** @var ReputationSite $reputationSite */
            $reputationSite = $this->reputationSiteHandler->getRepository()->findOneBy(['id' => $site]);
            $this->checkResourceFound($reputationSite, ReputationSite::NOT_FOUND_MESSAGE, $site);
        }

        $review = null;
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        if ($user instanceof User) {
            /** @var ReputationReview $review */
            $review = $this->reputationReviewHandler->getReviewForResponse($user, $engageId, $reputationSite);
        }

        return $review;
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "Gets statistics to display in the contractor portal",
     *    statusCodes = {
     *        200 = "Returned when expected data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\QueryParam(
     *      name="start",
     *      description="start date"
     * )
     *
     * @Rest\QueryParam(
     *      name="end",
     *      description="end date"
     * )
     *
     * @Rest\QueryParam(
     *      name="site",
     *      requirements="\d+",
     *      default="",
     *      description="review site to work with, defaults to trip advisor"
     * )
     *
     * @Rest\Get("/resolve/portal/contractor")
     *
     * @Permissions({"get.portal.contractor"})
     *
     * @param ParamFetcher $paramFetcher
     * @return array
     */
    public function getPortalForContractorAction(ParamFetcher $paramFetcher)
    {
        $reputationSite = null;
        $params = $paramFetcher->all();
        $launch = new DateTime(ResolveResponse::LAUNCH_DATE);
        $today = new DateTime();

        $interval = $launch->diff(new DateTime($params['start']));
        $start = $interval->invert || empty($params['start']) ? $launch : new DateTime($params['start']);
        $start->setTime(0, 0, 0);

        $interval = $today->diff(new DateTime($params['end']));
        $end = !$interval->invert || empty($params['end']) ? $today : new DateTime($params['end']);
        $end->setTime(23, 59, 59);

        if ($params['site']) {
            /** @var ReputationSite $reputationSite */
            $reputationSite = $this->reputationSiteHandler->getRepository()->findOneBy(['id' => $params['site']]);
            $this->checkResourceFound($reputationSite, ReputationSite::NOT_FOUND_MESSAGE, $params['site']);
        }

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $this->checkResourceFound($user, User::NOT_FOUND_MESSAGE, null);

        return [
            'reviews_pending' => $this->reputationReviewHandler->countPendingForContractor(null, $reputationSite),
            'reviews_proposed' => $this->reputationReviewHandler->countProposed(
                null,
                $reputationSite,
                $user,
                $start,
                $end
            ),
            'reviews_unpaid' => $this->reputationReviewHandler->countUnpaid(
                null,
                $reputationSite,
                $user,
                $start,
                $end
            ),
            'reviews_completed' => $this->reputationReviewHandler->countCompletedForContractor(
                null,
                $reputationSite,
                $user,
                $start,
                $end
            ),
            'current_amount_due' => $this->reputationReviewHandler->sumUnpaid(
                null,
                $reputationSite,
                $user,
                $start,
                $end
            ),
            'start' => $start,
            'end' => $end,
        ];
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "Gets period review statistics for a property",
     *    statusCodes = {
     *        200 = "Returned when Property Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\QueryParam(
     *      name="start",
     *      description="start date"
     * )
     *
     * @Rest\QueryParam(
     *      name="end",
     *      description="end date"
     * )
     *
     * @Rest\QueryParam(
     *      name="current_sites",
     *      description="only get results for current resolve settings"
     * )
     *
     * @Rest\QueryParam(
     *      name="sites",
     *      description="get results for specific site(s) (one or more comma separated); overrides current_sites"
     * )
     *
     * @Rest\Get("/resolve/property/statistics/{propertyHash}")
     *
     * @param ParamFetcher $paramFetcher
     *
     * @param $propertyHash
     *
     * @return array
     */
    public function getStatisticsByPropertyAction(
        ParamFetcher $paramFetcher,
        $propertyHash
    ) {
        $sites = [];
        $periodSlaPercent = 0;
        $params = $paramFetcher->all();
        $launch = new DateTime(ResolveResponse::LAUNCH_DATE);
        $today = new DateTime();

        $interval = $launch->diff(new DateTime($params['start']));
        $start = $interval->invert ? $launch : new DateTime($params['start']);
        $start->setTime(0, 0, 0);

        $interval = $today->diff(new DateTime($params['end']));
        $end = !$interval->invert ? $today : new DateTime($params['end']);
        $end->setTime(23, 59, 59);

        $data = [
            'sla_normal' => 0,
            'sla_critical' => 0,
            'pending_response' => 0,
            'pending_approval' => 0,
            'pending_resolve' => 0,
            'period_reviews' => 0,
            'previous_day_reviews' => 0,
            'period_completed' => 0,
            'period_sla_percent' => 0,
            'period_sla_normal' => 0,
            'period_sla_critical' => 0,
            'total_emails_sent' => 0,
            'start' => $start,
            'end' => $end,
            'user' => $user = $this->container->get('security.token_storage')->getToken()->getUser(),
        ];

        $yesterdayStart = new DateTime('yesterday');
        $yesterdayStart->setTime(0, 0, 0);

        $yesterdayEnd = new DateTime('yesterday');
        $yesterdayEnd->setTime(23, 59, 59);

        /** @var Property $property */
        $property = $this->propertyHandler->findOneBy(['hash' => $propertyHash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $propertyHash);
        $this->checkResourceFound(
            $property->getResolveSetting(),
            ResolveSetting::NOT_FOUND_MESSAGE_PROPERTY,
            $propertyHash
        );

        if ($params['sites']) {
            $sites = explode(',', $params['sites']);
        } elseif ($params['current_sites']) {
            $resolveSettingSites = $property->getResolveSetting()->getResolveSettingSites();
            /** @var ResolveSettingSite $resolveSettingSite */
            foreach ($resolveSettingSites as $resolveSettingSite) {
                $sites[] = $resolveSettingSite->getReputationSite();
            }
        }

        /** @var Reputation $reputation */
        $reputation = $this->reputationHandler->getRepository()->findOneBy(['property' => $property]);

        if ($reputation instanceof Reputation) {
            $pendingResponse = $this->reputationReviewHandler->countPendingResponse(
                $reputation,
                $sites,
                null,
                $start,
                $end
            );
            $pendingApprovalToday = $this->reputationReviewHandler->countPendingApproval(
                $reputation,
                $sites,
                null,
                $launch,
                $today
            );
            $pendingApproval = $this->reputationReviewHandler->countPendingApproval(
                $reputation,
                $sites,
                null,
                $start,
                $end
            );
            $pendingResolveToday = $this->reputationReviewHandler->countPendingResolve(
                $reputation,
                $sites,
                null,
                $launch,
                $today
            );
            $pendingResolve = $this->reputationReviewHandler->countPendingResolve(
                $reputation,
                $sites,
                null,
                $start,
                $end
            );
            $periodCompleted = $this->reputationReviewHandler->countCompletedForAnalyst(
                $reputation,
                $sites,
                null,
                $start,
                $end
            );
            $periodReviews = $this->reputationReviewHandler->countAll(
                $reputation,
                $sites,
                null,
                $start,
                $end
            );
            $previousDayReviews = $this->reputationReviewHandler->countAll(
                $reputation,
                $sites,
                null,
                $yesterdayStart,
                $yesterdayEnd
            );
            $periodSlaNormal = $this->reputationReviewHandler->countSlaNormal(
                $reputation,
                null,
                null,
                $start,
                $end
            );
            $periodSlaCritical = $this->reputationReviewHandler->countSlaCritical(
                $reputation,
                null,
                null,
                $start,
                $end
            );

            if ($property->getResolveSetting()) {
                $slaNormal = $property->getResolveSetting()->getSlaNormal();
                $slaCritical = $property->getResolveSetting()->getSlaCritical();
            } else {
                $slaNormal = 0;
                $slaCritical = 0;
            }

            if ($slaNormal + $slaCritical > 0) {
                $periodSlaPercent = $periodCompleted / ($slaNormal + $slaCritical) * 100;
            }

            $data = [
                'sla_normal' => $slaNormal,
                'sla_critical' => $slaCritical,
                'pending_response' => $pendingResponse,
                'pending_approval_today' => $pendingApprovalToday,
                'pending_approval' => $pendingApproval,
                'pending_resolve_today' => $pendingResolveToday,
                'pending_resolve' => $pendingResolve,
                'period_reviews' => $periodReviews,
                'previous_day_reviews' => $previousDayReviews,
                'period_completed' => $periodCompleted,
                'period_sla_percent' => $periodSlaPercent,
                'start' => $start,
                'end' => $end,
                'period_sla_normal' => $periodSlaNormal,
                'period_sla_critical' => $periodSlaCritical,
                'total_emails_sent' => $this->reputationEmailHandler->getTotalSent($reputation, $start, $end),
                'user' => $user = $this->container->get('security.token_storage')->getToken()->getUser(),
            ];
        }

        return $data;
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "Gets properties assigned to an analyst",
     *    statusCodes = {
     *        200 = "Returned when Property Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
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
     *      name="start",
     *      description="start date"
     * )
     *
     * @Rest\QueryParam(
     *      name="end",
     *      description="end date"
     * )
     *
     * @Rest\QueryParam(
     *      name="current_sites",
     *      description="only get results for current resolve settings"
     * )
     *
     * @Rest\QueryParam(
     *      name="sites",
     *      description="get results for specific site(s) (one or more comma separated); overrides current_sites"
     * )
     *
     * @Rest\Get("/resolve/portal/analyst")
     *
     * Permissions({"get.portal.analyst"})
     *
     * @param ParamFetcher $paramFetcher
     * @return array
     */
    public function getPortalForAnalystAction(ParamFetcher $paramFetcher)
    {
        $params = $paramFetcher->all();
        $launch = new DateTime(ResolveResponse::LAUNCH_DATE);
        $today = new DateTime();

        $interval = $launch->diff(new DateTime($params['start']));
        $start = $interval->invert ? $launch : new DateTime($params['start']);
        $start->setTime(0, 0, 0);

        $interval = $today->diff(new DateTime($params['end']));
        $end = !$interval->invert ? $today : new DateTime($params['end']);
        $end->setTime(23, 59, 59);

        $data = [
            'properties' => [],
            'period_stats' => [
                'period_reviews' => 0,
                'previous_day_reviews' => 0,
                'pending_response' => 0,
                'pending_approval' => 0,
                'pending_resolve' => 0,
                'period_completed' => 0,
                'total_accounts' => 0,
                'period_sla_normal' => 0,
                'period_sla_critical' => 0,
                'total_accounts_met_sla_normal' => 0,
                'total_accounts_met_sla_critical' => 0,
            ],
            'start' => $start,
            'end' => $end,
        ];

        $propertyList = $this->getResolvePropertiesAction();

        /** @var Property $property */
        foreach ($propertyList as $property) {
            $stats = $this->getStatisticsByPropertyAction($paramFetcher, $property->getHash());
            $data['properties'][] = [
                'property_hash' => $property->getHash(),
                'property_name' => $property->getName(),
                'monthly_sla_normal' => $property->getResolveSetting()->getSlaNormal(),
                'monthly_sla_critical' => $property->getResolveSetting()->getSlaCritical(),
                'analyst' => [
                    'full_name' => $property->getResolveSetting()->getAnalyst() instanceof User
                        ? $property->getResolveSetting()->getAnalyst()->getFullName()
                        : null,
                    'email_canonical' => $property->getResolveSetting()->getAnalyst() instanceof User
                        ? $property->getResolveSetting()->getAnalyst()->getEmailCanonical()
                        : null,
                ],
                'hotelier' => [
                    'full_name' => $property->getResolveSetting()->getHotelier() instanceof User
                        ? $property->getResolveSetting()->getHotelier()->getFullName()
                        : null,
                    'email_canonical' => $property->getResolveSetting()->getHotelier() instanceof User
                        ? $property->getResolveSetting()->getHotelier()->getEmailCanonical()
                        : null,
                ],
                'stats' => $stats,
            ];

            $data['period_stats']['period_reviews'] += $stats['period_reviews'];
            $data['period_stats']['previous_day_reviews'] += $stats['previous_day_reviews'];
            $data['period_stats']['pending_response'] += $stats['pending_response'];
            $data['period_stats']['pending_approval'] += $stats['pending_approval'];
            $data['period_stats']['pending_resolve'] += $stats['pending_resolve'];
            $data['period_stats']['period_completed'] += $stats['period_completed'];
            $data['period_stats']['period_completed'] += $stats['period_completed'];
            $data['period_stats']['total_accounts']++;

            //TODO derive sla from $rs->getSlaNormal() monthly value
            //will be sla * number of months in search range / number of days in those months * number of days in range
            if ($stats['period_sla_normal'] > $property->getResolveSetting()->getSlaNormal()) {
                $data['period_stats']['total_accounts_met_sla_normal']++;
            }

            //TODO derive sla plus from $rs->getSlaNormal() monthly value
            //will be sla * number of months in search range / number of days in those months * number of days in range
            if ($stats['period_sla_critical'] > $property->getResolveSetting()->getSlaCritical()) {
                $data['period_stats']['total_accounts_met_sla_critical']++;
            }

            $data['start'] = $stats['start'];
            $data['end'] = $stats['end'];
        }

        return $data;
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "Gets properties assigned to an analyst",
     *    statusCodes = {
     *        200 = "Returned when Property Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\QueryParam(
     *      name="start",
     *      description="start date"
     * )
     *
     * @Rest\QueryParam(
     *      name="end",
     *      description="end date"
     * )
     *
     * @Rest\QueryParam(
     *      name="site",
     *      requirements="\d+",
     *      default="",
     *      description="review site to work with, defaults to trip advisor"
     * )
     *
     * @Rest\Get("/resolve/analyst/statistics")
     *
     * Permissions({"get.period.statistics.analyst"})
     *
     * @param ParamFetcher $paramFetcher
     * @return array
     */
    public function getStatisticsByAnalystAction(
        ParamFetcher $paramFetcher
    ) {
        $periodStatistics = [
            'period_reviews' => 0,
            'previous_day_reviews' => 0,
            'pending_response' => 0,
            'pending_approval' => 0,
            'pending_resolve' => 0,
            'period_completed' => 0,
            'period_sla' => 0,
            'period_sla_plus' => 0,
            'sla' => 0,
            'sla_plus' => 0,
            'total_accounts' => 0,
            'total_accounts_met_sla' => 0,
            'total_accounts_met_sla_plus' => 0,
        ];

        //remove user restriction for soft launch
        $resolveSetting = $this->resolveSettingHandler->getRepository()->findBy([]);

        // TODO enable user restriction...
        //$user = $this->container->get('security.token_storage')->getToken()->getUser();
        //$resolveSetting = $this->resolveSettingHandler->getRepository()->findBy(['analyst' => $user]);

        /** @var ResolveSetting $rs */
        foreach ($resolveSetting as $rs) {
            $propertyStatistics = $this->getStatisticsByPropertyAction($paramFetcher, $rs->getProperty()->getHash());

            $periodStatistics['period_reviews'] += $propertyStatistics['period_reviews'];
            $periodStatistics['previous_day_reviews'] += $propertyStatistics['previous_day_reviews'];
            $periodStatistics['pending_response'] += $propertyStatistics['pending_response'];
            $periodStatistics['pending_approval'] += $propertyStatistics['pending_approval'];
            $periodStatistics['pending_resolve'] += $propertyStatistics['pending_resolve'];
            $periodStatistics['period_completed'] += $propertyStatistics['period_completed'];
            $periodStatistics['period_completed'] += $propertyStatistics['period_completed'];
            $periodStatistics['total_accounts']++;

            //TODO derive sla from $rs->getSlaNormal() monthly value
            //will be sla * number of months in search range / number of days in those months * number of days in range
            if ($propertyStatistics['period_sla'] > $rs->getSlaNormal()) {
                $periodStatistics['total_accounts_met_sla']++;
            }

            //TODO derive sla plus from $rs->getSlaNormal() monthly value
            //will be sla * number of months in search range / number of days in those months * number of days in range
            if ($propertyStatistics['period_sla_plus'] > $rs->getSlaCritical()) {
                $periodStatistics['total_accounts_met_sla_plus']++;
            }
        }

        return $periodStatistics;
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "get a review by engage id",
     *    statusCodes = {
     *        200 = "Returned when ReputationReview Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\Get("/resolve/review/{engageId}")
     *
     * @param $engageId
     *
     * @return array
     */
    public function getReviewByEngageIdAction($engageId)
    {
        /** @var ReputationReview $reputationReview */
        $reputationReview = $this->reputationReviewHandler->findOneBy(['engageId' => $engageId]);
        $this->checkResourceFound($reputationReview, ReputationReview::NOT_FOUND_MESSAGE_ENGAGE_ID, $engageId);

        return [
            'review' => $reputationReview,
            'tags' => $this->resolveReviewTagHandler->stringifyReviewTagIndices(
                $reputationReview->getResolveReviewTag()
            ),
            'responses' => $this->resolveResponseHandler->stringifyResolveResponseIndices(
                $reputationReview->getResolveResponse()
            ),
        ];
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "get a review by hash",
     *    statusCodes = {
     *        200 = "Returned when ReputationReview Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\Get("/resolve/review/hash/{hash}")
     *
     * @param $hash
     *
     * @return array
     */
    public function getReviewByHashAction($hash)
    {
        /** @var ReputationReview $reputationReview */
        $reputationReview = $this->reputationReviewHandler->findOneBy(['hash' => $hash]);
        $this->checkResourceFound($reputationReview, ReputationReview::NOT_FOUND_MESSAGE_HASH, $hash);

        return [
            'review' => $reputationReview,
            'tags' => $this->resolveReviewTagHandler->stringifyReviewTagIndices(
                $reputationReview->getResolveReviewTag()
            ),
            'responses' => $this->resolveResponseHandler->stringifyResolveResponseIndices(
                $reputationReview->getResolveResponse()
            ),
        ];
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "get a property hash by engage id",
     *    statusCodes = {
     *        200 = "Returned when data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\Get("/resolve/property/{engageId}")
     *
     * @param $engageId
     *
     * @return string
     */
    public function getPropertyHashByEngageIdAction($engageId)
    {
        /** @var ReputationReview $reputationReview */
        $reputationReview = $this->reputationReviewHandler->findOneBy(['engageId' => $engageId]);
        $this->checkResourceFound($reputationReview, ReputationReview::NOT_FOUND_MESSAGE_ENGAGE_ID, $engageId);

        return $reputationReview->getReputation()->getProperty()->getHash();
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "get a review hash by engage id",
     *    statusCodes = {
     *        200 = "Returned when data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\Get("/resolve/review_hash_from_engage_id/{engageId}")
     *
     * @param $engageId
     *
     * @return string
     */
    public function getReviewHashByEngageIdAction($engageId)
    {
        /** @var ReputationReview $reputationReview */
        $reputationReview = $this->reputationReviewHandler->findOneBy(['engageId' => $engageId]);
        $this->checkResourceFound($reputationReview, ReputationReview::NOT_FOUND_MESSAGE_ENGAGE_ID, $engageId);

        return $reputationReview->getHash();
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "get a list of contractors",
     *    statusCodes = {
     *        200 = "Returned when data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\Get("/resolve/contractors")
     *
     * @return array
     */
    public function getContractorsAction()
    {
        $return = [
            'result' => [],
        ];
        $contractors = $this->resolveResponseRatingHandler->getContractors();

        /** @var  User $contractor */
        foreach ($contractors as $contractor) {
            $return['result'][] = [
                'user' => $contractor,
                'total_proposals' => $this->resolveResponseHandler->getProposalCountByUser($contractor),
                'last_activity' => $this->resolveResponseHandler->getLastActivityByUser($contractor),
                'balance_to_date' => $this->resolveResponseRatingHandler->getBalanceToDateByUser($contractor),
            ];
        }

        return $return;
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "get data for one contractor",
     *    statusCodes = {
     *        200 = "Returned when data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\Get("/resolve/contractor/{userHash}")
     *
     * @param $userHash
     * @return array
     */
    public function getContractorAction($userHash)
    {
        $collatedUnpaid = [];
        $return = [
            'result' => [],
        ];

        $contractor = $this->userHandler->findOneBy(['hash' => $userHash]);
        $this->checkResourceFound($contractor, User::NOT_FOUND_MESSAGE_HASH, $userHash);

        $unpaid = $this->resolveResponseRatingHandler->getUnpaidByUser($contractor);
        $paid = $this->resolveContractorInvoiceHandler->getPaidByUser($contractor);

        /** @var ResolveResponseRating $rating */
        foreach ($unpaid as $type => $rating) {
            //TODO doctrine groups
            $collatedUnpaid[$type]['created_at'] = $rating->getCreatedAt();
            $collatedUnpaid[$type]['rating'] = $rating->getRating();
            $collatedUnpaid[$type]['payment_value'] = $rating->getPaymentValue();
            $collatedUnpaid[$type]['resolve_response']['reputation_review']['reputation']['property']['name'] =
                $rating->getResolveResponse()->getReputationReview()->getReputation()->getProperty()->getName();
            $collatedUnpaid[$type]['resolve_response']['reputation_review']['engage_id'] =
                $rating->getResolveResponse()->getReputationReview()->getEngageId();
            $collatedUnpaid[$type]['resolve_response']['reputation_review']['proposed_at'] =
                $rating->getResolveResponse()->getReputationReview()->getProposedAt();
        }

        $return['result'] = [
            'user' => $contractor,
            'unpaid' => $collatedUnpaid,
            'paid' => $paid,
            'balance_to_date' => $this->resolveResponseRatingHandler->getBalanceToDateByUser($contractor),
            'paid_to_date' => $this->resolveContractorInvoiceHandler->getPaidToDateByUser($contractor),
        ];

        return $return;
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = true,
     *    description = "get data for one contractor",
     *    statusCodes = {
     *        200 = "Returned when data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     *
     * @Rest\Post("/resolve/contractor/{userHash}/invoice/new")
     *
     * @param $userHash
     * @return array
     */
    public function postContractorInvoiceAction($userHash)
    {
        $return = [];
        $total = 0;

        $contractor = $this->userHandler->findOneBy(['hash' => $userHash]);
        $this->checkResourceFound($contractor, User::NOT_FOUND_MESSAGE_HASH, $userHash);

        $unpaidRatings = $this->resolveResponseRatingHandler->getUnpaidByUser($contractor);
        if (count($unpaidRatings) > 0) {
            $invoice = new ResolveContractorInvoice();
            $invoice->setUser($contractor);

            /** @var ResolveResponseRating $unpaidRating */
            foreach ($unpaidRatings as $unpaidRating) {
                $total += $unpaidRating->getPaymentValue();
                $unpaidRating->setResolveContractorInvoice($invoice);
                $this->resolveResponseRatingHandler->save($unpaidRating);
            }

            $invoice->setPaymentValue($total);
            $return = $this->resolveContractorInvoiceHandler->save($invoice);
        }

        return $return;
    }
}
