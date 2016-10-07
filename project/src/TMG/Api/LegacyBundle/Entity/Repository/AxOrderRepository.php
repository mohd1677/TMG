<?php

namespace TMG\Api\LegacyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class AxOrderRepository extends EntityRepository
{

    /**
     * Function to determin master order number
     *
     * @param string $orderNumber
     * @param string $masterOrderNumber
     *
     * @return string
     */
    public function getMaster($orderNumber, $masterOrderNumber)
    {
        $result = '';

        $e1Account = $this->findOneBy(['orderNumber' => $orderNumber])->getLegacyOrderNumber();

        if ($e1Account) {
            $result = $e1Account;
        } elseif ($masterOrderNumber) {
            $masterOrder = $this->findOneBy(['orderNumber' => $masterOrderNumber]);
            if ($masterOrder) {
                $masterOrderAccount = $masterOrder->getCustomer()->getCustomerNumber();

                if ($masterOrderAccount) {
                    $result = $masterOrderAccount;
                } else {
                    $result = $masterOrderNumber;
                }
            }
        }

        return $result;
    }

    /**
     * Get customer number that has shared ad space.
     *
     * @param string $masterOrderNumber
     *
     * @return string|null
     */
    public function getSharedCustomerNumber($masterOrderNumber)
    {
        try {
            $result = $this->createQueryBuilder('o')
                ->select('c.customerNumber')
                ->join('o.customer', 'c')
                ->where('o.orderNumber = :orderNumber')
                ->setParameter('orderNumber', $masterOrderNumber)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
            $result = null;
        }

        return $result;
    }
}
