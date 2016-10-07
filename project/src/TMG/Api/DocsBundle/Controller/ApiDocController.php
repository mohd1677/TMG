<?php

namespace TMG\Api\DocsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use TMG\Api\GlobalBundle\Controller\GlobalController;

use TMG\Api\DocsBundle\Entity\ApiDocMeta;

class ApiDocController extends GlobalController
{
    protected $indexView = 'ApiDocsBundle:ApiDoc:index.html.twig';
    protected $routeView = 'ApiDocsBundle:ApiDoc:routes.html.twig';
    protected $detailView = 'ApiDocsBundle:ApiDoc:detail.html.twig';

    public function initialize()
    {
        $this->docMetaRepo = $this->em->getRepository('ApiDocsBundle:ApiDocMeta');
    }

    public function indexAction()
    {
        return $this->render(
            $this->indexView,
            $this->viewData()
        );
    }

    public function routesAction()
    {
        $publicRecords = $this->docMetaRepo->findBy(array('public' => true), array('routeUrl' => 'ASC'));
        $privateRecords = $this->docMetaRepo->findBy(array('public' => false), array('routeUrl' => 'ASC'));
        $publicRoutes = [];
        $privateRoutes = [];

        foreach ($publicRecords as $record) {
            $publicRoutes[] = array(
                'name' => $record->getName(),
                'path' => $record->getRouteUrl(),
                'summary' => $record->getSummary(),
            );
        }

        foreach ($privateRecords as $record) {
            $privateRoutes[] = array(
                'name' => $record->getName(),
                'path' => $record->getRouteUrl(),
                'summary' => $record->getSummary(),
            );
        }

        $data = array(
            'publicRoutes' => $publicRoutes,
            'privateRoutes' => $privateRoutes,
        );

        return $this->render(
            $this->routeView,
            $this->viewData() + $data
        );
    }

    public function viewAction(Request $request, $name)
    {
        $routeInfo = $this->docMetaRepo->findOneBy(array('name' => $name));

        $data = array(
            'routeInfo' => $routeInfo,
        );

        return $this->render(
            $this->detailView,
            $this->viewData() + $data
        );
    }
}
