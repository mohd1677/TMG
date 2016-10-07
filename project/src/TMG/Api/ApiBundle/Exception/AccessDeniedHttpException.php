<?php
/**
 * AccessDeniedHttpException
 */
namespace TMG\Api\ApiBundle\Exception;

use TMG\Api\ApiBundle\Exception\ClassicHttpException;

/**
 * AccessDeniedHttpException.
 *
 * @package Exception
 */
class AccessDeniedHttpException extends ClassicHttpException
{
    /**
     * Constructor.
     *
     * @param string     $message  The internal exception message
     * @param \Exception $previous The previous exception
     * @param int        $code     The internal exception code
     */
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct(401, $message, $previous, array(), $code);
    }
}
