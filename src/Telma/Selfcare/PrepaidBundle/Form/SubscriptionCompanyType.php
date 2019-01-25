<?php

namespace Telma\Selfcare\PrepaidBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Telma\Selfcare\PrepaidBundle\Form\CompanyType;

class SubscriptionCompanyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subscription',null,array(
                'required' => false
            ))
            ->add('company','entity', array(
                'class' => 'TelmaSelfcarePrepaidBundle:Company',
                'choice_label' => 'companyName'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Telma\Selfcare\PrepaidBundle\Entity\SubscriptionCompany'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'telma_selfcare_prepaidbundle_subscriptioncompany';
    }
}
