<?php

namespace TMG\Api\VideoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException as HttpException;
use TMG\Api\ApiBundle\Entity\VideoStatus;
use TMG\Api\ApiBundle\Exception\BadRequestHttpException;
use TMG\Api\ApiBundle\Controller\ApiController;
use TMG\Api\ApiBundle\Entity\Description;
use TMG\Api\ApiBundle\Entity\Property;
use TMG\Api\ApiBundle\Entity\Video;
use TMG\Api\PropertiesBundle\Handler\PropertyHandler;
use TMG\Api\UserBundle\Entity\User;
use TMG\Api\UtilityBundle\Annotations\Permissions;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use TMG\Api\VideoBundle\Handler\VideoHandler;
use TMG\Api\VideoBundle\Handler\VideoStatusHandler;

/**
 * Class VideoController
 *
 * @Rest\NamePrefix("tmg_api_")
 * @package TMG\Api\VideoBundle\Controller
 */
class VideoController extends ApiController
{
    /** @var  VideoHandler */
    private $videoHandler;

    /** @var  PropertyHandler */
    private $propertyHandler;

    /** @var  VideoStatusHandler */
    private $videoStatusHandler;

    /**
     * @param VideoHandler $videoHandler
     * @param PropertyHandler $propertyHandler
     */
    public function __construct(
        VideoHandler $videoHandler,
        PropertyHandler $propertyHandler,
        VideoStatusHandler $videoStatusHandler
    ) {

        $this->videoHandler = $videoHandler;
        $this->propertyHandler = $propertyHandler;
        $this->videoStatusHandler = $videoStatusHandler;
    }

    /**
     * @ApiDoc(
     *    section = "Video",
     *    resource = true,
     *    description = "Gets a video by id",
     *    statusCodes = {
     *        200 = "Returned when Video Data is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no Video data is found"
     *    }
     * )
     * @Rest\Get("/video/{id}")
     *
     * @return Video
     */
    public function getVideoAction($id)
    {
        return $this->videoHandler->getRepository()->find($id);
    }

    /**
     * @ApiDoc(
     *    section = "Video",
     *    resource = true,
     *    description = "Adds a local event to property",
     *    statusCodes = {
     *        201 = "Returned when a Local Event has been created",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *    }
     * )
     *
     * @Rest\Post("/video/{propertyHash}")
     *
     * @Permissions({"post.property.video"})
     *
     * @param Request $request
     * @param $propertyHash
     *
     * @throws HttpException
     *
     * @return Video
     */
    public function postVideoAction(Request $request, $propertyHash)
    {
        /** @var Property $property */
        $property = $this->propertyHandler->findOneBy(["hash" => $propertyHash]);
        $this->checkResourceFound($property, Property::NOT_FOUND_MESSAGE, $propertyHash);

        if ($property->getVideo()) {
            throw new BadRequestHttpException("Video already exists.  Use PUT or PATCH to get desired result.");
        }

        $parameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            Video::$requiredPostFields
        );

        /** @var Video $video */
        $video = new Video();
        $this->mapArrayToEntity($video, $parameters);

        if ($request->request->has("description")) {
            $parameters = $this->validateAndMapRequestToParametersArray(
                $request->request->get("description"),
                Description::$fillable
            );

            $description = new Description();
            $this->mapArrayToEntity($description, $parameters);
            $video->setDescription($description);
        }

        /** @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        /** @var VideoStatus $status */
        $status = $this->videoStatusHandler->getRepository()->find(4);

        $video->setStatus($status);
        $video->setProperty($property);
        $video->setPublishedBy($user);
        $video->setSubmittedBy($user);

        return $this->videoHandler->post($video);
    }

    /**
     * @ApiDoc(
     *    section = "Video",
     *    resource = true,
     *    description = "Updates a property ",
     *    statusCodes = {
     *        201 = "Returned when a Local Event has been updated",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials"
     *    }
     * )
     *
     * @Rest\Put("/video/{id}")
     *
     * @Permissions({"put.property.video"})
     *
     * @param Request $request
     * @param int $id
     *
     * @return Video
     */
    public function putVideoAction(Request $request, $id)
    {
        /** @var Video $video */
        $video = $this->videoHandler->getRepository()->find($id);
        $this->checkResourceFound($video, Video::NOT_FOUND_MESSAGE, $id);

        $parameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            Video::$requiredPostFields
        );

        $this->mapArrayToEntity($video, $parameters);

        if ($request->request->has("description")) {
            $parameters = $this->validateAndMapRequestToParametersArray(
                $request->request->get("description"),
                Description::$fillable
            );

            $description = $video->getDescription() ? $video->getDescription() : new Description();

            $this->mapArrayToEntity($description, $parameters);
            $video->setDescription($description);
        }

        return $this->videoHandler->put($video);

    }

    /**
     * @ApiDoc(
     *    section = "Video",
     *    resource = true,
     *    description = "Updates a property video",
     *    statusCodes = {
     *        201 = "Returned when a Video has been updated",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials"
     *    }
     * )
     *
     * @Rest\Patch("/video/{id}")
     *
     * @Permissions({"patch.property.video"})
     *
     * @param Request $request
     * @param int $id
     *
     * @return Video
     */
    public function patchVideoAction(Request $request, $id)
    {
        /** @var Video $video */
        $video = $this->videoHandler->getRepository()->find($id);
        $this->checkResourceFound($video, Video::NOT_FOUND_MESSAGE, $id);

        $parameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            Video::$requiredPostFields,
            true
        );

        $this->mapArrayToEntity($video, $parameters, [], true);

        if ($request->request->has("description")) {
            $parameters = $this->validateAndMapRequestToParametersArray(
                $request->request->get("description"),
                Description::$fillable,
                true
            );

            $description = $video->getDescription() ? $video->getDescription() : new Description();

            $this->mapArrayToEntity($description, $parameters, [], true);
            $video->setDescription($description);
        }

        return $this->videoHandler->patch($video);
    }

    /**
     * @ApiDoc(
     *    section = "Video",
     *    resource = true,
     *    description = "Updates a local event",
     *    statusCodes = {
     *        201 = "Returned when a Local Event has been updated",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials"
     *    }
     * )
     *
     * @Rest\Delete("/video/{id}")
     *
     * @Permissions({"delete.property.video"})
     *
     * @param int $id
     *
     * @return boolean
     */
    public function deleteVideoAction($id)
    {
        $video = $this->videoHandler->getRepository()->find($id);
        $this->checkResourceFound($video, Video::NOT_FOUND_MESSAGE, $id);
        $this->videoHandler->delete($id);
        return;
    }
}
