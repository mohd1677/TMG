<?php

namespace TMG\Api\UtilityBundle\Driver;

use Doctrine\Common\Annotations\Reader as AnnotationReader;
use FOS\UserBundle\Model\User;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use TMG\Api\UtilityBundle\Annotations;
use TMG\Api\ApiBundle\Handler\SecurityHandler;
use Symfony\Component\HttpFoundation\Response;
use TMG\Api\ApiBundle\Exception as Exception;

/**
 * Class PermissionAnnotationDriver
 *
 * Parses security annotations and funnels them to the appropriate validator.
 *
 * @package Driver
 */
class PermissionAnnotationDriver extends ContainerAware
{
    const ANNOTATION_NAMESPACE = "TMG\\Api\\UtilityBundle\\Annotations";
    const ANNOTATION_CLASS = "TMG\\Api\\UtilityBundle\\Annotations\\Permissions";

    /**
     * Instance of annotation reader
     *
     * @var AnnotationReader
     */
    private $reader;

    /**
     * @var SecurityHandler
     */
    private $securityHandler;

    /**
     * @param SecurityHandler $handler
     */
    public function setSecurityHandler(SecurityHandler $handler)
    {
        $this->securityHandler = $handler;
    }

    /**
     * @param AnnotationReader $reader
     */
    public function setReader(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        //\Doctrine\Common\Util\Debug::dump($event->getController()); exit;

        //Break if no controller available
        if (!is_array($controller = $event->getController())) {
            return;
        }

        // Break if no user available
        if (!$this->container->get('security.token_storage')->getToken()) {
            return;
        }
        
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        //Load Controller Reflection Object
        $object = new \ReflectionObject($controller[0]);

        //Get Reflection Method
        $method = $object->getMethod($controller[1]);

        //Get method's security annotations
        $annotation = $this->reader->getMethodAnnotation($method, self::ANNOTATION_CLASS);

        //Loop through security annotations and validate against appropriate handler
        if ($annotation) {
            foreach ($annotation->getPermissions() as $permission) {
                if (!$this->securityHandler->canUser($user, $permission)) {
                    $this->throwException($user, $permission);
                }
            }
        }
    }

    /**
     * Throws the correct exception based on the User object passed in (or null)
     *
     * @param User $user
     */
    private function throwException(User $user = null, $permission = null)
    {
        $exception = new Exception\General\AnonymousUserAccessDeniedException();
        throw $exception;
    }
}
