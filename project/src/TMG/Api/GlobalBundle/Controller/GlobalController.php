<?php

namespace TMG\Api\GlobalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use Doctrine\ORM\EntityManager;

abstract class GlobalController extends Controller
{
    // Globals
    protected $em;
    protected $container;
    protected $templating;
    protected $session;

    public function __construct(EntityManager $em, Container $container, $templating)
    {
        $this->em = $em;
        $this->templating = $templating;
        $this->container = $container;
        $this->session = $this->container->get('session');
        $this->initialize();
    }

    public function initialize()
    {
        // Extended By Controllers using this class
    }

    public function viewData()
    {
        return array();
    }

    public function render($view, array $parameters = array(), Response $response = null)
    {
        return $this->templating->renderResponse($view, $parameters, $response);
    }

    public function createNotFoundException($message = 'Not Found', \Exception $previous = null)
    {
        return new NotFoundHttpException($message, $previous);
    }

    public function createForm($type, $data = null, array $options = array())
    {
        return $this->container->get('form.factory')->create($type, $data, $options);
    }

    public function createDeleteForm($id)
    {
        return $this->container->get('form.factory')->createBuilder('form', array('id'=>$id))
            ->add('id', 'hidden')
            ->getForm();
    }
}
