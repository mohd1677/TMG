<?php

namespace TMG\Api\PropertiesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use TMG\Api\ApiBundle\Controller\ApiController;
use TMG\Api\ApiBundle\Entity\HotelRevenueCalculation;
use TMG\Api\UserBundle\Entity\User;
use TMG\Api\PropertiesBundle\Handler\HotelRevenueHandler;

class HotelRevenueController extends ApiController
{
    /** @var HotelRevenueHandler */
    private $hotelRevenueHandler;

    public function __construct(HotelRevenueHandler $handler)
    {
        $this->hotelRevenueHandler = $handler;
    }

    /**
     * @param Request $request
     *
     * @Rest\Post("/hotel-revenue")
     *
     * @return HotelRevenueCalculation
     */
    public function postSaveHotelRevenueAction(Request $request)
    {
        $parameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            HotelRevenueCalculation::$requiredPostFields
        );

        $hotelRevenueCalculation = new HotelRevenueCalculation();
        $this->mapArrayToEntity($hotelRevenueCalculation, $parameters);

        /** @var TokenInterface $token */
        $token = $this->container->get('security.token_storage')->getToken();

        /** @var User $user */
        $user = $token->getUser();

        $hotelRevenueCalculation->setUser($user);
        $hotelRevenueCalculation->calculateValues();

        return $this->hotelRevenueHandler->post($hotelRevenueCalculation);
    }

    /**
     * @Rest\Get("/hotel-revenue")
     * @return array
     */
    public function getUserHotelRevenueAction()
    {
        /** @var TokenInterface $token */
        $token = $this->container->get('security.token_storage')->getToken();

        /** @var User $user */
        $user = $token->getUser();

        $worksheets = $this->hotelRevenueHandler->getRepository()->findBy(['user' => $user], ['updatedAt' => 'DESC']);

        /** @var HotelRevenueCalculation $worksheet */
        foreach ($worksheets as $worksheet) {
            $worksheet->calculateValues();
        }

        return $worksheets;
    }

    /**
     * @param $worksheetId
     *
     * @return HotelRevenueCalculation
     *
     * @Rest\Get("/hotel-revenue/{worksheetId}")
     */
    public function getHotelRevenueAction($worksheetId)
    {
        /** @var TokenInterface $token */
        $token = $this->container->get('security.token_storage')->getToken();

        /** @var User $user */
        $user = $token->getUser();

        /** @var HotelRevenueCalculation $worksheet */
        $worksheet = $this->hotelRevenueHandler->getRepository()->findOneBy(['id' => $worksheetId, 'user' => $user]);

        $this->checkResourceFound($worksheet, HotelRevenueCalculation::NOT_FOUND_MESSAGE, $worksheetId);

        $worksheet->calculateValues();

        return $worksheet;
    }

    /**
     * @param Request $request
     * @param int|string $worksheetId
     *
     * @return HotelRevenueCalculation
     *
     * @Rest\Patch("/hotel-revenue/{worksheetId}")
     *
     */
    public function patchSaveHotelRevenueAction(Request $request, $worksheetId)
    {
        /** @var TokenInterface $token */
        $token = $this->container->get('security.token_storage')->getToken();

        /** @var User $user */
        $user = $token->getUser();

        /** @var HotelRevenueCalculation $worksheet */
        $worksheet = $this->hotelRevenueHandler->getRepository()->findOneBy(['id' => $worksheetId, 'user' => $user]);

        $this->checkResourceFound($worksheet, HotelRevenueCalculation::NOT_FOUND_MESSAGE, $worksheetId);

        $parameters = $this->validateAndMapRequestToParametersArray(
            $request->request->all(),
            HotelRevenueCalculation::$requiredPostFields
        );

        $this->mapArrayToEntity($worksheet, $parameters);
        $worksheet->calculateValues();

        return $this->hotelRevenueHandler->patch($worksheet);
    }

    /**
     * @param int|string $worksheetId
     *
     * @return void
     *
     * @Rest\Delete("/hotel-revenue/{worksheetId}")
     */
    public function deleteHotelRevenueAction($worksheetId)
    {
        /** @var TokenInterface $token */
        $token = $this->container->get('security.token_storage')->getToken();

        /** @var User $user */
        $user = $token->getUser();

        /** @var HotelRevenueCalculation $worksheet */
        $worksheet = $this->hotelRevenueHandler->getRepository()->findOneBy(['id' => $worksheetId, 'user' => $user]);
        $this->checkResourceFound($worksheet, HotelRevenueCalculation::NOT_FOUND_MESSAGE, $worksheetId);
        $this->hotelRevenueHandler->delete($worksheetId);

        return;
    }
}
