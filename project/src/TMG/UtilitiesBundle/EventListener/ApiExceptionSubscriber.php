<?php

namespace TMG\UtilitiesBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use TMG\UtilitiesBundle\Exception\ApiProblemException;
use TMG\UtilitiesBundle\Problem\ApiProblem;

/**
 * Class ApiExceptionSubscriber. Subscribes to Symfony's "kernel.exception" event to customize the response sent when
 * an exception is thrown.
 */
class ApiExceptionSubscriber implements EventSubscriberInterface
{
    /** @var  bool Whether or not the app is in debug mode */
    private $debug;

    /**
     * ApiExceptionSubscriber constructor.
     *
     * @param $debug
     */
    public function __construct($debug)
    {
        $this->debug = $debug;
    }

    /**
     * Called when the "kernel.exception" even is triggered.
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // Get the exception from the event.
        $exception = $event->getException();

        // Get the status code from the exception.
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;

        // If the exception is an instance of ApiProblemException, then it may have some useful info on it.
        if ($exception instanceof ApiProblemException) {
            $apiProblem = $exception->getApiProblem();
        } else {
            // If not we'll create a simple ApiProblem.
            $apiProblem = new ApiProblem($statusCode);

            // if we have a class that implements the HTTPExceptionInterface or if we are not in production and the
            // getMessage method exists, then we can "safely" set the detail key.
            if ($exception instanceof HttpExceptionInterface
                || ($this->debug && method_exists($exception, 'getMessage'))
            ) {
                $apiProblem->set('detail', $exception->getMessage());
            }
        }

        $response = new JsonResponse(
            $apiProblem->toArray(),
            $apiProblem->getStatusCode()
        );
        $response->headers->set('Content-Type', 'application/problem+json');

        $event->setResponse($response);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [KernelEvents::EXCEPTION => 'onKernelException'];
    }
}
