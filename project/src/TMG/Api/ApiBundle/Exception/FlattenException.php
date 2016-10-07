<?php
/**
 * FlattenException
 */
namespace TMG\Api\ApiBundle\Exception;

use \Symfony\Component\Debug\Exception\FlattenException as BaseFlattenException;

/**
 * Class FlattenException
 *
 * @package Exception
 */
class FlattenException extends BaseFlattenException
{
    /**
     * An array of translation parameters
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * {@inheritDoc}
     */
    public static function create(\Exception $exception, $statusCode = null, array $headers = array())
    {
        $e = parent::create($exception, $statusCode, $headers);

        // Makes sure to set our custom parameters, if it descends from our subclass
        if ($exception instanceof HttpException) {
            $e->setParameters($exception->getParameters());
        }

        return $e;
    }

    /**
     * Returns the translation token parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Sets the translation token parameters
     *
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }
}
