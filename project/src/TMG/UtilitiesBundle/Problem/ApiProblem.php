<?php

namespace TMG\UtilitiesBundle\Problem;

use Symfony\Component\HttpFoundation\Response;

class ApiProblem
{
    const TYPE_VALIDATION_ERROR = 'validation_error';
    const TYPE_INVALID_REQUEST_BODY_FORMAT = 'invalid_body_format';

    // Map types to titles.
    private static $titles = [
        self::TYPE_VALIDATION_ERROR => 'There was a validation error',
        self::TYPE_INVALID_REQUEST_BODY_FORMAT => 'Invalid request body format',
    ];

    private $statusCode;

    private $type;

    private $title;

    private $extraData = [];

    /**
     * ApiException constructor.
     *
     * @param int $statusCode
     * @param string $type
     */
    public function __construct($statusCode, $type = null)
    {
        // If weren't given a type, we'll use "about:blank".
        if ($type === null) {
            $type = 'about:blank';
            $title = isset(Response::$statusTexts[$statusCode])
                ? Response::$statusTexts[$statusCode]
                : 'Unknown Status Code';
        } else {
            // Otherwise, we'll see if we have a title corresponding to the type.
            if (!isset(self::$titles[$type])) {
                throw new \InvalidArgumentException('No title for type '.$type);
            }
            $title = self::$titles[$type];
        }

        $this->statusCode = $statusCode;
        $this->type = $type;
        $this->title = $title;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_merge(
            array(
                'status' => $this->statusCode,
                'type' => $this->type,
                'title' => $this->title,
            ),
            $this->extraData
        );
    }

    /**
     * @param mixed $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $this->extraData[$name] = $value;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
