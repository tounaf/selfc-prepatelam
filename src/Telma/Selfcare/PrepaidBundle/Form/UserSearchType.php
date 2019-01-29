<?php

namespace Telma\Selfcare\PrepaidBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;


class UserSearchType extends UserType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->remove('companies')
            ->add('companies','entity',array(
                'class' => 'TelmaSelfcarePrepaidBundle:Company',
                'choice_label' =>'companyName',
                'multiple' =>false,
                'expanded' => false,
                'allow_extra_fields' => true
            ))
        ;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'telma_selfcare_prepaidbundle_user_search';
    }

    public function getParent()
    {
        return new UserType();
    }
}
