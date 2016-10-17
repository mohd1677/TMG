<?php

namespace TMG\Api\ApiBundle\Pagination;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class PaginationFactory
 *
 * @package TMG\Api\ApiBundle\Pagination
 */
class PaginationFactory
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * PaginationFactory constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Takes in a QueryBuilder, request parameters, a route to generate URLs for and parameters for that route
     * and returns a PaginatedCollection.
     *
     * @param QueryBuilder $queryBuilder
     * @param array        $requestParams
     * @param string       $route
     * @param array        $routeParams
     *
     * @return PaginatedCollection
     */
    public function createCollection(
        QueryBuilder $queryBuilder,
        array $requestParams,
        $route,
        array $routeParams = []
    ) {
        $adapter = new DoctrineORMAdapter($queryBuilder);

        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($requestParams['count']);
        $pagerfanta->setCurrentPage($requestParams['page']);

        // Pagerfanta returns a traversable object with the results inside, which confuses
        // the serializer. To get an array, we loop over the traversable object and push
        // each result into an array.
        $results = [];

        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $results[] = $result;
        }

        $paginatedCollection = new PaginatedCollection($results, $pagerfanta->getNbResults());

        $createLinkUrl = function ($targetPage) use ($route, $routeParams) {
            return $this->router->generate($route, array_merge(
                $routeParams,
                ['page' => $targetPage]
            ));
        };

        $paginatedCollection->addLink('self', $createLinkUrl($pagerfanta->getCurrentPage()));
        $paginatedCollection->addLink('first', $createLinkUrl(1));
        $paginatedCollection->addLink('last', $createLinkUrl($pagerfanta->getNbPages()));

        if ($pagerfanta->hasNextPage()) {
            $paginatedCollection->addLink('next', $createLinkUrl($pagerfanta->getNextPage()));
        }

        if ($pagerfanta->hasPreviousPage()) {
            $paginatedCollection->addLink('prev', $createLinkUrl($pagerfanta->getPreviousPage()));
        }

        return $paginatedCollection;
    }
}
