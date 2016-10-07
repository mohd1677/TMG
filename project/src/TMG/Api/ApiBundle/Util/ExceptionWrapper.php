<?php
/**
 * ExceptionWrapper
 */
namespace TMG\Api\ApiBundle\Util;

use Symfony\Component\Form\FormInterface;

/**
 * Class ExceptionWrapper
 *
 * @package Util
 */
class ExceptionWrapper
{
    /**
     * HTTP Status Code
     *
     * @var integer
     */
    private $code;

    /**
     * Translated message interpolated with replacements
     *
     * @var string
     */
    private $message;

    /**
     * An array of form errors or other errors
     *
     * @var array
     */
    private $errors;

    /**
     * A stack trace
     *
     * @var array
     */
    private $trace;

    /**
     * @param array               $data
     * @param bool                $debug
     */
    public function __construct($data, $debug)
    {
        $exception = $data['exception'];

        $this->code = $data['status_code'];

        $this->message = $data['message'];

        if (isset($data['errors'])) {
            $this->errors = $data['errors'];
        }

        if ($debug) {
            $this->trace = $exception->getTrace();
        }

    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return FormInterface
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Returns the message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
