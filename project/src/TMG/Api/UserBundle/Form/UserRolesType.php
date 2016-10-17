<?php

namespace TMG\Api\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserRolesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('role', 'text', array(
                'label' => 'Role'
            ))
            ->add('platform', 'choice', array(
                'label' => 'Role Platform',
                'choices' => $this->getPlatforms(),
            ))
            ->add('description', 'textarea', array(
                'label' => 'Role Description',
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
            'data_class' => 'TMG\Api\UserBundle\Entity\UserRoles'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tmg_api_userbundle_userroles';
    }

    /**
     * Role Platforms
     */
    private function getPlatforms()
    {
        return array(
            '' => 'Select a Role Platform',
            'all' => 'All',
            'hotelcoupons' => 'Hotel Coupons',
            'dashboard' => 'My TMG',
            'api' => 'API',
        );

    }
}
