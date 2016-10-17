<?php

namespace TMG\Api\DocsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ApiDocMetaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('route', 'text', array(
                'label' => 'Route Param',
            ))
            ->add('routeUrl', 'text', array(
                'label' => 'Route URL',
            ))
            ->add('name', 'text', array(
                'label' => 'Readable Name',
            ))
            ->add('params', 'collection', array(
                'label' => 'Request Params',
                'type' => new DocParamsType(),
                'prototype' => true,
                'prototype_name' => 'param',
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'options' => array(
                    'attr' => array('label' => 'Param'),
                ),
            ))
            ->add('instructions', 'ckeditor', array(
                'label' => 'Request Instructions',
                'required' => false,
            ))
            ->add('public', 'checkbox', array(
                'label' => 'Public?',
                'required' => false,
            ))
            ->add('summary', 'textarea', array(
                'label' => 'Main Page Summary',
                'required' => false,
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TMG\Api\DocsBundle\Entity\ApiDocMeta'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tmg_api_docsbundle_apidocmeta';
    }
}
