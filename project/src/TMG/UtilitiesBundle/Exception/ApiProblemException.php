<?php

namespace TMG\UtilitiesBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use TMG\UtilitiesBundle\Problem\ApiProblem;

class ApiProblemException extends HttpException
{
    /** @var ApiProblem  */
    private $apiProblem;

    /**
     * ApiProblemException constructor. We override the parent constructor to take in an ApiProblem argument.
     *
     * @param ApiProblem      $apiProblem
     * @param \Exception|null $previous
     * @param array           $headers
     * @param null            $code
     */
    public function __construct(ApiProblem $apiProblem, \Exception $previous = null, array $headers = [], $code = null)
    {
        $this->apiProblem = $apiProblem;
        $statusCode = $apiProblem->getStatusCode();
        $message = $apiProblem->getTitle();

        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    /**
     * @return ApiProblem
     */
    public function getApiProblem()
    {
        return $this->apiProblem;
    }
}
