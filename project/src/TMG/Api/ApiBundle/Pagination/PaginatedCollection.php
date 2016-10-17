<?php

namespace TMG\Api\ApiBundle\Pagination;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class PaginatedCollection
 *
 * @package TMG\Api\ApiBundle\Pagination
 *
 * @Serializer\ExclusionPolicy("all")
 */
class PaginatedCollection
{
    /**
     * @var mixed
     *
     * @Serializer\Expose
     * @Serializer\Groups({"All"})
     */
    private $items;

    /**
     * @var int
     *
     * @Serializer\Expose
     * @Serializer\Groups({"All"})
     */
    private $total;

    /**
     * @var int
     *
     * @Serializer\Expose
     * @Serializer\Groups({"All"})
     */
    private $count;

    /**
     * @var array
     *
     * @Serializer\Expose
     * @Serializer\Groups({"All"})
     */
    private $links = array();

    /**
     * PaginatedCollection constructor.
     *
     * @param mixed $items
     * @param int $total
     */
    public function __construct($items, $total)
    {
        $this->items = $items;
        $this->total = $total;
        $this->count = count($items);
    }

    /**
     * @param string $rel The link rel to use.
     * @param string $url The url to assign to the rel.
     */
    public function addLink($rel, $url)
    {
        $this->links[$rel] = $url;
    }
}
