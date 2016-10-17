<?php
/**
 * PagingInfo
 **/
namespace TMG\Api\ApiBundle\Util;

use FOS\RestBundle\Request\ParamFetcherInterface;
use TMG\Api\UtilityBundle\Date\DateUtility;

/**
 * Class PagingInfo
 *
 * This class encapsulates paging information.
 *
 * @package Util
 */
class PagingInfo
{
    /**
     * The minimum count per page
     */
    const MIN_COUNT = 1;

    /**
     * The maximum count per page
     */
    const MAX_COUNT = 100;

    /**
     * The default count per page
     */
    const DEFAULT_COUNT = 50;

    /**
     * The default page
     */
    const DEFAULT_PAGE = 1;

    /**
     * Default order
     */
    const DEFAULT_ORDER = 'ASC';

    /**
     *
     */
    const DEFAULT_RANGE = '365';

    /**
     * The count per page
     *
     * @var int
     */
    protected $count = self::DEFAULT_COUNT;

    /**
     * The 1 indexed page number
     *
     * @var int
     */
    protected $page = self::DEFAULT_PAGE;

    /**
     * The order of the result set
     *
     * @var string
     */
    protected $order = self::DEFAULT_ORDER;

    /**
     * The field to sort by
     *
     * @var mixed|null
     */
    protected $sortBy = null;

    /**
     * @var null
     */
    protected $start = null;

    /**
     * @var null
     */
    protected $end = null;

    /**
     * @var null
     */
    protected $range = self::DEFAULT_RANGE;

    /**
     * @var null
     */
    protected $search = null;

    /**
     * Takes in a ParamFetcherInterface and calculates the page/count/offset correctly.
     *
     * @param ParamFetcherInterface $paramFetcher
     */
    public function __construct(ParamFetcherInterface $paramFetcher = null, $limit = true)
    {
        if ($paramFetcher) {
            try {
                $count = $paramFetcher->get('count');

                // Make sure the count is within range so we don't blow out the server returning something invalid.
                if (is_int($count)  && ($count < self::MIN_COUNT || ($limit && $count > self::MAX_COUNT))) {
                    $count = self::DEFAULT_COUNT;
                }

                $this->count = $count;
            } catch (\InvalidArgumentException $e) {
            }

            try {
                $page = $paramFetcher->get('page');

                // Make sure the page isn't less than 1
                if ($page < 1) {
                    $page = self::DEFAULT_PAGE;
                }

                $this->page = $page;
            } catch (\InvalidArgumentException $e) {
            }

            try {
                $this->order = $paramFetcher->get('order');
            } catch (\InvalidArgumentException $e) {
            }

            try {
                $this->sortBy = $paramFetcher->get('sortBy');
            } catch (\InvalidArgumentException $e) {
            }

            try {
                $this->range = $paramFetcher->get('range');
                $dateRange = DateUtility::getReputationDateRange($this->range);
                $this->end = $dateRange['end'];
                $this->start = $dateRange['start'];
                $this->range = $dateRange['range'];
            } catch (\InvalidArgumentException $e) {
            }

            try {
                $this->search = $paramFetcher->get('search');
            } catch (\InvalidArgumentException $e) {
            }
        }

    }

    /**
     * Returns the page count
     *
     * @return int
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * Returns the page number
     *
     * @return int
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * Returns the page count
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Returns the page number
     *
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Returns the 0 indexed page number ($page - 1)
     *
     * @return int
     */
    public function getActualPage()
    {
        return $this->page - 1;
    }

    /**
     * Returns the calculated result set offset
     *
     * @return int
     */
    public function getOffset()
    {
        return ($this->page - 1) * $this->count;
    }

    /**
     * Returns the order of the request, ascending or descending.
     *
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * sets the order of the request, ascending or descending.
     *
     * @return string
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return mixed|null
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

    /**
     * @param mixed|null $sortBy
     */
    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }

    /**
     * @return mixed|null
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param \DateTime|null $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return mixed|null
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param \DateTime|null $end
     */
    public function setEnd($end)
    {
        $this->$end = $end;
    }

    /**
     * @return mixed|null
     */
    public function getRange()
    {
        return $this->range;
    }

    /**
     * @param mixed|null $range
     */
    public function setRange($range)
    {
        $this->$range = $range;
    }

    /**
     * @return mixed|null
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param $search
     */
    public function setSearch($search)
    {
        if (strlen($search) > 0) {
            $this->search = $search;
        } else {
            $this->search = null;
        }

    }
}
