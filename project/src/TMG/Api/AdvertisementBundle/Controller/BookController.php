<?php

namespace TMG\Api\AdvertisementBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use TMG\Api\ApiBundle\Controller\ApiController;

use TMG\Api\AdvertisementBundle\Handler\BookHandler;

use FOS\RestBundle\Controller\Annotations as Rest;

use /** @noinspection PhpUnusedAliasInspection */
    Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class BookController
 *
 * @Rest\NamePrefix("tmg_api_")
 * @package TMG\Api\AdvertisementBundle\Controller
 */
class BookController extends ApiController
{
    /**
     * @var BookHandler
     */
    protected $bookHandler;
 
     /**
     * @param BookHandler $bookHandler
     */
    public function __construct(
        BookHandler $bookHandler
    ) {
        $this->bookHandler = $bookHandler;
    }
    /**
     * @ApiDoc(
     *    section = "AdvertisementBundle",
     *    resource = true,
     *    description = "Get a list of all the Books along with code.",
     *    statusCodes = {
     *        200 = "Returned when array is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no Book data is found"
     *    }
     * )
     * @Rest\Get("/advertisement/books")
     */

    public function getBooksAction()
    {
        $bookCodes = $this->bookHandler->getAllBooks();

         return $bookCodes;
    }

    /**
     * @ApiDoc(
     *    section = "AdvertisementBundle",
     *    resource = true,
     *    description = "Get a list of all the Books along with code.",
     *    statusCodes = {
     *        200 = "Returned when array is returned",
     *        400 = "Returned when bad json or required parameters are missing from the request",
     *        401 = "Returned when the user is not logged in or has invalid credentials",
     *        404 = "Returned when no ResolveResponse data is found"
     *    }
     * )
     * @Rest\Get("/admin/ax/ad-change/")
     *
     * @param Request $request
     *
     * @return AdChange
     */
    public function getAdChangeAction(Request $request)
    {
        $book = $request->query->get('book', '');
        $issue = $request->query->get('issue', '');
        $type = $request->query->get('type', '');
        $renewed = $request->query->get('renewed', '');
        $dropped = $request->query->get('dropped', '');

        $adChange = $this->bookHandler->getAdChange($book, $issue, $type, $renewed, $dropped);

         return new JsonResponse([
            'success' => true,
            'results' => $adChange,
         ]);
    }
}
