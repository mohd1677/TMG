<?php

namespace TMG\Api\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

class RegistrationController extends BaseController
{
    protected function setFlash($action, $value)
    {
        $value = $this->container->get('translator')->trans($value, array(), 'FOSUserBundle');
        $this->container->get('session')->setFlash($action, $value);
    }
}
