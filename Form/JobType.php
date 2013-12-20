<?php

namespace Teneleven\Bundle\CareerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JobType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('slug','text' , array('required' => false))
            ->add('status')
            ->add('releaseDate', 'date', array('required' => false))
            ->add('expirationDate', 'date', array('required' => false))
            ->add('summary', 'textarea', array('required' => false))
            ->add('description', 'textarea', array('required' => false))
            ->add('location', 'text', array('required' => false))
            ->add('reportTo', 'text', array('required' => false))
            ->add('contact', 'text', array('required' => false))
            ->add('isPublished', 'checkbox', array('required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Teneleven\Bundle\CareerBundle\Entity\Job'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'teneleven_bundle_careerbundle_job';
    }
}
