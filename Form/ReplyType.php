<?php

namespace Teneleven\Bundle\CareerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReplyType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName','text', array('required' => false, 'attr' => array('class' => 'input-large')))
            ->add('lastName','text', array('required' => false, 'attr' => array('class' => 'input-large')))
            ->add('phone','text', array('required' => false, 'attr' => array('class' => 'input-large')))
            ->add('email','text', array('required' => false, 'attr' => array('class' => 'input-large')))
            ->add('qualifications','text', array('required' => false, 'attr' => array('class' => 'input-large')))
            ->add('resume', 'file', array('required' => false, 'attr' => array('class' => 'input-large')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Teneleven\Bundle\CareerBundle\Entity\Reply'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'teneleven_bundle_careerbundle_reply';
    }
}
