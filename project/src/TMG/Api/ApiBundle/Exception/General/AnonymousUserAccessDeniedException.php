<?php

namespace TMG\Api\ApiBundle\Exception\General;

use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpKernel\Exception\HttpException as SymfonyHttpException;

/**
 * Class AnonymousUserAccessDeniedException
 *
 * @package Exception\General
 */
class AnonymousUserAccessDeniedException extends SymfonyHttpException
{
    /**
     * [Constructor]
     *
     * @param array      $parameters The array of string replacement tokens
     * @param \Exception $previous   The previous exception if these are being chained
     */
    public function __construct(array $parameters = [], \Exception $previous = null)
    {
        parent::__construct(Codes::HTTP_UNAUTHORIZED, "You don't have permission", $previous, $parameters);
    }
}
