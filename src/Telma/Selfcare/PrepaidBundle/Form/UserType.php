<?php

namespace Telma\Selfcare\PrepaidBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Telma\Selfcare\PrepaidBundle\Entity\Company;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('nom')
            ->add('prenom')
            ->add('isAdmin','choice', array(
                'choices' => array(
                    'Simple utilisateur' => false,
                    'Administrateur' => true
                )
            ))
            ->add('company', 'entity', array(
                'class' => 'TelmaSelfcarePrepaidBundle:Company'
            ))
//            ->add('isValid')
//            ->add('isAdmin')
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
