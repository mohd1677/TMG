<?php

namespace TMG\Api\AdvertisementBundle\Handler;

use TMG\Api\ApiBundle\Handler\ApiHandler;
use Doctrine\Common\Collections\ArrayCollection;
use TMG\Api\ApiBundle\Entity\Repository\BooksRepository;
use TMG\Api\ApiBundle\Entity\Books;

/**
 * Class BookHandler
 * @package TMG\Api\AdvertisementBundle\Handler
 *
 * @property BooksRepository $repository
 */
class BookHandler extends ApiHandler
{
     /**
     * @var BooksRepository
     */
    protected $repository;

    /**
     * @return array
     */
    public function getAllBooks()
    {
        $bookRepository = $this->em->getRepository('ApiBundle:Books');

        return $bookRepository->getBookList();
    }

     /**
     * @param $book
     * @param $issue
     * @param $type
     * @param $renewed
     * @param $dropped
     *
     * @return array|Reports
     */
    public function getAdChange($book, $issue, $type, $renewed, $dropped)
    {
        $this->booksRepo = $this->em->getRepository('ApiBundle:Books');
        $this->contractRepo = $this->em->getRepository('ApiBundle:Contract');
        $this->productTypesRepo = $this->em->getRepository('ApiBundle:ProductTypes');

        $reports = [];

        $bookId = $this->booksRepo->getBookIdByCode($book);
        $typeId = $this->productTypesRepo->getIdByType($type);

        $date = substr($issue, 0, 2) . '-' . substr($issue, 2, 2) . '-01';
        $prevIssueNumber = date('ym', strtotime($date . ' - 1 month'));
        $currentIssueNumber = $issue;

        $currentItems = $this->contractRepo->adChangeItems($bookId['id'], $typeId['id'], (int) $currentIssueNumber);

        $currentItemIds = [];
        foreach ($currentItems as $result) {
            $status = '';
            $uFields = '';

            array_push($currentItemIds, $result['id']);

            if ($result['propertyNumber']) {
                $accountNum = $result['propertyNumber'];
            } else {
                $accountNum = $result['axNumber'];
            }

            if ($result['masterOrderE1Account']) {
                $masterOrder = $result['masterOrderE1Account'];
            } elseif ($result['masterOrderAccount']) {
                $masterOrder = $result['masterOrderAccount'];
            } elseif ($result['masterOrderNumber']) {
                $masterOrder = $result['masterOrderNumber'];
            } else {
                $masterOrder = '';
            }

            // adding 800 contract codes
            $current800Codes = $this->contractRepo
                ->get800ContractProductCodes($result['property'], $currentIssueNumber);
            foreach ($current800Codes as $current800Code) {
                $result['code'] = $result['code'] . ' ' . $current800Code['code'];
            }

            if ($result['startIssue'] == $currentIssueNumber) {
                $status = '';

                $previousItem = $this->contractRepo->adChangePreviousItem(
                    $bookId['id'],
                    $typeId['id'],
                    (int) $result['property'],
                    (int) $currentIssueNumber
                );

                if ($previousItem) {
                    $prevIssue = '';
                    if ($previousItem['masterOrderE1Account']) {
                        $order = $previousItem['masterOrderE1Account'];
                    } elseif ($previousItem['masterOrderAccount']) {
                        $order = $previousItem['masterOrderAccount'];
                    } elseif ($previousItem['masterOrderNumber']) {
                        $order = $previousItem['masterOrderNumber'];
                    } else {
                        $order = '';
                    }

                    // adding 800 contract codes
                    $prev800Codes = $this->contractRepo
                        ->get800ContractProductCodes($result['property'], $prevIssueNumber);
                    foreach ($prev800Codes as $prev800Code) {
                        $previousItem['code'] = $previousItem['code'] . ' ' . $prev800Code['code'];
                    }

                    if ($prevIssueNumber == $previousItem['endIssue']) {
                        $uFields = '';
                        if ($previousItem['code'] != $result['code']) {
                            $status = 'Changed';
                            $uFields .= 'Product Code';
                        }

                        if ($previousItem['position'] != $result['position']) {
                            $status = 'Changed';
                            if ($uFields) {
                                $uFields .= ' Position';
                            } else {
                                $uFields .= 'Position';
                            }
                        }

                        if (!$uFields) {
                            if ($renewed) {
                                $status = 'Renewed';
                                $uFields = 'None';
                            }
                        }
                    } elseif ($previousItem['orderNumber'] == $result['orderNumber']) {
                        $status = 'Skipped';
                        $updatedFields = 'All';
                    } else {
                        $status = 'Reinstated';
                        $updatedFields = 'All';
                    }

                    $prev = array(
                        'code' => $previousItem['code'],
                        'description' => $previousItem['description'],
                        'color' => $previousItem['color'],
                        'position' => $previousItem['position'],
                        'eight_hundred' => '',
                    );
                } else {
                    $status = 'New Ad';
                    $uFields = 'All';
                    $prev = array(
                        'code' => '',
                        'description' => '',
                        'color' => '',
                        'position' => '',
                        'eight_hundred' => '',
                    );
                }

                if ($status) {
                    $row = array(
                        'status' => $status,
                        'updated_fields' => $uFields,
                        'customer' => $accountNum,
                        'order' => $masterOrder,
                        'prev' => $prev,
                        'result' => $result,
                        'id' => $result['property'],
                        'eight_hundred' => ''
                    );

                    array_push($reports, $row);
                }
            }
        }

        if ($dropped) {
            $itemIds = $this->contractRepo->adChangeDroppedItems(
                $bookId['id'],
                $typeId['id'],
                (int) $prevIssueNumber,
                $currentItemIds
            );

            foreach ($itemIds as $result) {
                $status = null;
                $uFields = '';

                if ($result['propertyNumber']) {
                    $accountNum = $result['propertyNumber'];
                } else {
                    $accountNum = $result['axNumber'];
                }

                if ($result['masterOrderE1Account']) {
                    $orderNumber = $result['masterOrderE1Account'];
                } elseif ($result['masterOrderAccount']) {
                    $orderNumber = $result['masterOrderAccount'];
                } elseif ($result['masterOrderNumber']) {
                    $orderNumber = $result['masterOrderNumber'];
                } else {
                    $orderNumber = '';
                }

                //items in next order
                $count = $this->contractRepo->nextOrderItemCount(
                    $bookId['id'],
                    $typeId['id'],
                    (int) $result['property'],
                    (int) $currentIssueNumber
                );

                // Item was dropped since there is no continue order
                if ($count == 0) {
                    $status = 'Dropped';
                    $uFields = 'None';

                    // adding 800 contract codes
                    $dropped800Codes = $this->contractRepo
                        ->get800ContractProductCodes($result['property'], $currentIssueNumber);
                    foreach ($dropped800Codes as $dropped800Code) {
                        $result['code'] = $result['code'] . ' ' . $dropped800Code['code'];
                    }

                    $prev['code'] = $result['code'];
                    $prev['description'] = $result['description'];
                    $prev['color'] = $result['color'];
                    $prev['position'] = $result['position'];

                    $row = array(
                        'status' => $status,
                        'updated_fields' => $uFields,
                        'customer' => $accountNum,
                        'order' => $orderNumber,
                        'prev' => $prev,
                        'result' => $result,
                        'id' => $result['property'],
                        'eight_hundred' => ''
                    );
                    array_push($reports, $row);
                }
            }
        }

        return $reports;
    }
}
