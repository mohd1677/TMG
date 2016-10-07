<?php
/**
 * ObjectWithIdNotFoundException
 */
namespace TMG\Api\ApiBundle\Exception\General;

use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Util\Codes;

/**
 * ObjectWithIdNotFoundException.
 *
 * @package Exception\General
 */
class ObjectWithIdNotFoundException extends HttpException
{
    protected $gcErrorConstant = "GENERAL_OBJECT_WITH_ID_NOT_FOUND";
    protected static $requiredParameters = ['type', 'id'];

    /**
     * [Constructor]
     *
     * @param array      $parameters The array of string replacement tokens
     * @param \Exception $previous   The previous exception if these are being chained
     */
    public function __construct(array $parameters = [], \Exception $previous = null)
    {
        parent::__construct(Codes::HTTP_NOT_FOUND, $parameters, $previous);
    }
}
