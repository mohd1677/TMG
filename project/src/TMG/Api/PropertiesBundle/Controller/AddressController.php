<?php
/**
 * AddressController
 */
namespace TMG\Api\PropertiesBundle\Controller;

use TMG\Api\ApiBundle\Controller\ApiController;
use FOS\RestBundle\Controller\Annotations as Rest;
use TMG\Api\PropertiesBundle\Handler\AddressHandler;
use TMG\Api\PropertiesBundle\Handler\PropertyHandler;

/**
 * Class AddressController
 *
 * @Rest\NamePrefix("tmg_api_")
 */
class AddressController extends ApiController
{
    /**
     * @var PropertyHandler
     */
    protected $propertyHandler;

    /**
     * @var AddressHandler
     */
    protected $addressHandler;

    /**
     * [Constructor]
     *
     * @param PropertyHandler $propertyHandler
     */
    public function __construct(PropertyHandler $propertyHandler, AddressHandler $addressHandler)
    {
        $this->propertyHandler = $propertyHandler;
        $this->addressHandler = $addressHandler;
    }
}
