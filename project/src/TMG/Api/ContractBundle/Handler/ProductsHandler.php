<?php

namespace TMG\Api\ContractBundle\Handler;

use TMG\Api\ApiBundle\Entity\Repository\ProductsRepository;
use TMG\Api\ApiBundle\Handler\ApiHandler;

class ProductsHandler extends ApiHandler
{
    /** @var  ProductsRepository $repository */
    protected $repository;

    /**
     * @param array $productCodes
     * @return array
     */
    public function getProducts(array $productCodes)
    {
        return $this->getRepository()->findBy(['code' => $productCodes]);
    }
}
