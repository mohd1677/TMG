<?php
/**
 * ApiController
 */
namespace TMG\Api\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;

use TMG\Api\ApiBundle\Handler\ApiHandler;
use TMG\Api\ApiBundle\Util\PagingInfo;
use TMG\Api\ApiBundle\Exception as Exception;
use TMG\Api\ApiBundle\Behavior\ArrayToEntityMapTrait;

/**
 * Class ApiController
 * @package TMG\Api\ApiBundle\Controller
 */
abstract class ApiController extends FOSRestController
{
    /**
     * TRAIT <---------------
     */
    use ArrayToEntityMapTrait;

    /**
     * Takes in a ParamFetch and returns an object containing "count" and "offset" parameters
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return PagingInfo
     */
    protected function getPagingInfo(ParamFetcherInterface $paramFetcher)
    {
        return new PagingInfo($paramFetcher);
    }

    /**
     * Returns an array containing the $fields extracted from the request, throwing errors
     * if required parameters are not set.
     *
     * @param array $request
     * @param array   $fields  Array of fields. Key is the field name, value is a boolean required value1
     * @param boolean $isPatch Should missing fields be preserved (patch, or field defaults on post)
     *
     * @return array
     */
    protected function validateAndMapRequestToParametersArray($request, $fields, $isPatch = false)
    {
        $parameters = [];
        $errors = [];

        foreach ($fields as $field => $required) {
            $value = array_key_exists($field, $request) ? $request[$field] : false;

            if ($required && !$value && !$isPatch) {
                $errors[] = $field;
                continue;
            }

            if ($value) {
                $parameters[$field] = $value;
            } elseif (!$isPatch) {
                $parameters[$field] = null;
            }
        }

        if (count($errors)) {
            $message = "The required keys " . implode(', ', $errors) . " are not found in the request.";

            throw new Exception\BadRequestHttpException($message);
        }

        return $parameters;
    }

    /**
     * To be used in instances where the value an unchangeable Entity like State in Address.  The only thing
     * changing here is the manyToMany object being used.
     *
     * @param array $parameters
     * @param ApiHandler $handler
     * @param $key; The key in the parameters array
     * @param $findBy; How to find resource like id or name
     * @param $notFoundMsg; Exception message if resource can't be found.
     *
     * @return array
     */
    protected function getMappedObjectToArray(array $parameters, ApiHandler $handler, $key, $findBy, $notFoundMsg)
    {
        if (array_key_exists($key, $parameters)) {
            $resource = $handler->findOneBy([$findBy => $parameters[$key]]);
            $this->checkResourceFound($resource, $notFoundMsg, $parameters[$key]);
            $parameters[$key] = $resource;
        }

        return $parameters;
    }

    /**
     *
     * @param object      $resource
     * @param ApiHandler $handler
     *
     * @return mixed
     */
    protected function post($resource, ApiHandler $handler)
    {
        try {
            $resource = $handler->post($resource);

            $view = $this->createPostResponse($resource);
        } catch (Exception\ValidationException $e) {
            $view = $this->createValidationExceptionResponse($e);
        }

        return $view;
    }

    /**
     * Base resource PATCH method
     *
     * @param             $resource
     * @param ApiHandler $handler
     *
     * @return mixed
     */
    protected function patch($resource, ApiHandler $handler)
    {
        try {
            $resource = $handler->patch($resource);
            $view = $this->createPatchResponse($resource);
        } catch (Exception\ValidationException $e) {
            $view = $this->createValidationExceptionResponse($e);
        }

        return $view;
    }

    /**
     * Base resource PUT method
     *
     * @param             $resource
     * @param ApiHandler $handler
     *
     * @return mixed
     */
    protected function put($resource, ApiHandler $handler)
    {
        try {
            $resource = $handler->put($resource);

            $view = $this->createPutResponse($resource);
        } catch (Exception\ValidationException $e) {
            $view = $this->createValidationExceptionResponse($e);
        }

        return $view;
    }

    /**
     * Base resource delete method
     *
     * @param int $id
     * @param ApiHandler $handler
     *
     * @return mixed
     */
    protected function delete($id, ApiHandler $handler)
    {
        $handler->delete($id);
        $view = $this->createDeleteResponse();

        return $view;
    }

    /**
     * Creates a Response object for a POST request
     *
     * @param $resource
     *
     * @return View
     */
    protected function createPostResponse($resource)
    {
        $view = View::create()
            ->setStatusCode(Codes::HTTP_CREATED)
            ->setData($resource);

        return $view;
    }

    /**
     * Creates a response object for a PUT request
     *
     * @param $resource
     *
     * @return View
     */
    protected function createPutResponse($resource)
    {
        $view = View::create()
            ->setStatusCode(Codes::HTTP_OK)
            ->setData($resource);

        return $view;
    }

    /**
     * Creates a response for a PATCH request
     *
     * @param $resource
     *
     * @return View
     */
    protected function createPatchResponse($resource)
    {
        $view = View::create()
            ->setStatusCode(Codes::HTTP_OK)
            ->setData($resource);

        return $view;
    }

    /**
     * Creates as response for a DELETE request
     *
     * @return View
     */
    protected function createDeleteResponse()
    {
        $view = View::create()
            ->setStatusCode(Codes::HTTP_NO_CONTENT);

        return $view;
    }

    /**
     * Checks to make sure resource was found.
     *
     * @param $resource
     * @param $message
     * @param $findBy
     */
    protected function checkResourceFound($resource, $message, $findBy)
    {
        if (!$resource) {
            throw new Exception\NotFoundHttpException(
                sprintf($message, $findBy)
            );
        }
    }

    /**
     * This function produces a view object response for a validation exception.
     *
     * @param Exception\ValidationException $e
     *
     * @return View
     */
    protected function createValidationExceptionResponse(Exception\ValidationException $e)
    {
        $data = [
            'code' => $e->getStatusCode(),
            'status' => $e->getStatusCode(),
            'message' => 'Validation Error: ' . implode(', ', $e->getErrors()),
            'errors' => $e->getErrors(),
        ];

        $view = View::create()
            ->setStatusCode($e->getStatusCode())
            ->setData($data);

        return $view;
    }

    /**
     * This function produces a view object response for a validation exception.
     *
     * @param Exception\InvalidFormException $e
     *
     * @return View
     */
    protected function createFormExceptionResponse(Exception\InvalidFormException $e)
    {
        $data = [
            'code' => $e->getStatusCode(),
            'message' => $e->getMessage(),
            'errors' => $e->getForm(),
            'data' => $e->getData(),
        ];

        $view = View::create()
            ->setStatusCode($e->getStatusCode())
            ->setData($data);

        return $view;
    }
}
