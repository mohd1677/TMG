<?php

namespace TMG\Api\GlobalBundle\Controller;

use TMG\Api\GlobalBundle\Controller\GlobalController;
use TMG\Api\GlobalBundle\Form\Type\ContactType;

use Symfony\Component\HttpFoundation\Request;

class ContactController extends GlobalController
{
    public function indexAction(Request $request)
    {

        $form = $this->createForm(new ContactType());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Your contact request has been sent!');
            return $this->redirect($this->generateUrl('api_docs'));
        }

        return $this->render(
            'ApiGlobalBundle::contact.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
}
