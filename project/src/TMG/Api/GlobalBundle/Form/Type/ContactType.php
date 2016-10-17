<?php

namespace TMG\Api\GlobalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TMG\Api\GlobalBundle\Entity\Contact'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Name',
                'attr' => array(
                    'placeholder' => 'So we know whom to address our response to',
                ),
            ))
            ->add('email', 'email', array(
                'label' => 'Email',
                'attr' => array(
                    'placeholder' => 'So we know how to reach you',
                ),
            ))
            ->add('subject', 'text', array(
                'label'=> 'Subject',
                'attr' => array(
                    'placeholder' => 'So we can determine who to best respond',
                ),
            ))
            ->add('message', 'textarea', array(
                'label' => 'Details',
                'attr' => array(
                    'placeholder' => 'Provide any information that might be useful',
                ),
            ));
    }

    public function getName()
    {
        return 'contact';
    }
}
