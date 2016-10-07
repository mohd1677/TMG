<?php
/**
 * TransformationFailedException
 */
namespace TMG\Api\ApiBundle\Exception;

/**
 * Class TransformationFailedException
 *
 * Indicates a value transformation error.
 *
 * @package Exception
 */
class TransformationFailedException extends ClassicHttpException
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
        parent::__construct(500, $message, $previous, array(), $code);
    }
}