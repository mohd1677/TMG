<?php

namespace TMG\Api\DocsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocParamsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Param Name',
            ))
            ->add('type', 'text', array(
                'label' => 'Param Type',
            ))
            ->add('example', 'text', array(
                'label' => 'Param Example',
            ))
            ->add('required', 'checkbox', array(
                'label' => 'Required Parameter?',
                'required' => false,
            ))
            ->add('description', 'textarea', array(
                'label' => 'Param Description',
                'required' => false,
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TMG\Api\DocsBundle\Entity\DocParams'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tmg_api_docsbundle_docparams';
    }
}
