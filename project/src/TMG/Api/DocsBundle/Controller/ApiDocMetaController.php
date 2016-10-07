<?php

namespace TMG\Api\DocsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormError;
use Doctrine\Common\Collections\ArrayCollection;

use TMG\Api\GlobalBundle\Controller\GlobalController;

use TMG\Api\DocsBundle\Entity\ApiDocMeta;
use TMG\Api\DocsBundle\Entity\DocParams;
use TMG\Api\DocsBundle\Form\ApiDocMetaType;

/**
 * ApiDocMeta controller.
 *
 */
class ApiDocMetaController extends GlobalController
{
    private $docMetaRepo;

    protected $indexView          = 'ApiDocsBundle:ApiDocMeta:admin_index.html.twig';
    protected $newView            = 'ApiDocsBundle:ApiDocMeta:admin_new.html.twig';
    protected $editView            = 'ApiDocsBundle:ApiDocMeta:admin_edit.html.twig';

    protected $formClass          = 'TMG\Api\DocsBundle\Form\ApiDocMetaType';
    protected $entityClass        = 'TMG\Api\DocsBundle\Entity\ApiDocMeta';


    public function initialize()
    {
        $this->docMetaRepo = $this->em->getRepository('ApiDocsBundle:ApiDocMeta');
    }

    public function indexAction()
    {
        $routeMeta = $this->docMetaRepo->findAll();
        $routesList = $this->container->get('router')->getRouteCollection()->all();
        $routes = array();
        foreach ($routesList as $route => $params) {
            if (!preg_match('/^admin_|^_|^fos_user/', $route)) {
                $doc = false;

                foreach ($routeMeta as $record) {
                    if ($record->getRoute() == $route) {
                        $doc = true;
                    }
                }

                $routes[] = array(
                    'name' => $route,
                    'path' => $params->getPath(),
                    'doc' => $doc,
                );
            }
        }
        $data = array(
            'routes' => $routes,
        );

        return $this->render(
            $this->indexView,
            $this->viewData() + $data
        );
    }

    /**
     * New Action
     *
     */
    public function newAction(Request $request, $route)
    {
        $routesList = $this->container->get('router')->getRouteCollection()->all();
        $routes = array();
        foreach ($routesList as $getRoute => $params) {
            if (!preg_match('/cms_|^_|^fos_user/', $getRoute)) {
                $routes[] = array(
                    'name' => $getRoute,
                    'path' => $params->getPath(),
                );
            }
        }
        $routeExists = $this->docMetaRepo->findOneBy(array('route' => $route));
        if ($routeExists) {
            $redirect = $this->container->get('router')->generate('admin_docs_edit', array(
                'id' => $routeExists->getId(),
            ));

            return new RedirectResponse($redirect);
        }
        $entity  = new $this->entityClass;
        $entity->setRoute($route);
        $form = $this->createForm(new $this->formClass, $entity);
        $flash = $this->container->get('session')->getFlashBag();

        if ($request->isMethod('post')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->em->persist($entity);
                $this->em->flush();

                $flash->add('success', 'Doc Meta created successfully!');

                $redirect = $this->container->get('router')->generate('admin_docs_edit', array(
                    'id' => $entity->getId(),
                ));

                return new RedirectResponse($redirect);
            }
        }

        $data = array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'route'  => $route,
        );

        return $this->render(
            $this->newView,
            $this->viewData() + $data
        );
    }

    /**
     * Displays a form to edit an existing ApiDocMeta entity.
     *
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->docMetaRepo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ApiDocMeta entity.');
        }



        $route = $entity->getRoute();
        $form = $this->createForm(new $this->formClass, $entity);
        $deleteForm = $this->createDeleteForm($id);


        $data = array(
            'route' => $route,
            'entity'      => $entity,
            'form'        => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        );

        return $this->render(
            $this->editView,
            $this->viewData() + $data
        );
    }

    /**
     * Edits an existing ApiDocMeta entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $flash = $this->container->get('session')->getFlashBag();
        $entity = $this->docMetaRepo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ApiDocMeta entity.');
        }

        $originalParams = new ArrayCollection();
        foreach ($entity->getParams() as $p) {
            $originalParams->add($p);
        }

        $route = $entity->getRoute();
        $form = $this->createForm(new $this->formClass, $entity);
        $deleteForm = $this->createDeleteForm($id);

        $form->handleRequest($request);

        if ($form->isValid()) {
         // remove the relationship between the tag and the Task
            foreach ($originalParams as $p) {
                if (false === $entity->getParams()->contains($p)) {
                    $entity->getParams()->removeElement($entity);
                    $this->em->remove($p);
                }
            }

            $this->em->persist($entity);
            $this->em->flush();

            $flash->add('success', 'Documentation Updated successfully!');
            $redirect = $this->container->get('router')->generate('admin_docs_edit', array(
                'id' => $entity->getId(),
            ));

            return new RedirectResponse($redirect);
        }

        $data = array(
            'route' => $route,
            'entity'      => $entity,
            'form'        => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        );

        return $this->render(
            $this->editView,
            $this->viewData() + $data
        );
    }

    /**
     * Deletes a ApiDocMeta entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        $flash = $this->container->get('session')->getFlashBag();

        if ($form->isValid()) {
            $entity = $this->docMetaRepo->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ApiDocMeta entity.');
            }

            $this->em->remove($entity);
            $this->em->flush();

            $flash->add('success', 'Documentation deleted successfully!');
        }

        $redirect = $this->container->get('router')->generate('admin_docs');
        return new RedirectResponse($redirect);
    }
}
