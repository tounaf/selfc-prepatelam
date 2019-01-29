<?php

namespace Telma\Selfcare\PrepaidBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email','email',array(
                'required' => true
            ))
            ->add('nom','text',array(
                'required' => true
            ))
            ->add('prenom')
            ->add('isValid','choice',array(
                'choices' => array(
                    0=>'Inactif',
                    1=>'Actif'
                )
            ))
            ->add('isAdmin','choice',array(
                'choices' =>array(
                    0=>'Simple Utilisateur',
                    1=>'Administrateur'
                )
            ))
            ->add('companies','entity',array(
                'class' => 'TelmaSelfcarePrepaidBundle:Company',
                'choice_label' =>'companyName',
                'multiple' =>true,
                'expanded' => true,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Telma\Selfcare\PrepaidBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'telma_selfcare_prepaidbundle_user';
    }
}
