<?php

namespace Teneleven\Bundle\CareerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Validator\Constraints\Blank;

class ReplyType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName','text', array('attr' => array('class' => 'input-large')))
            ->add('lastName','text', array('attr' => array('class' => 'input-large')))
            ->add('phone','text', array('attr' => array('class' => 'input-large')))
            ->add('email','text', array('attr' => array('class' => 'input-large')))
            ->add('qualifications','textarea', array('attr' => array('class' => 'input-large')))
            ->add('file', 'file', array('required' => false, 'mapped' => false, 'attr' => array('class' => 'input-large')))
            //Honeypot Check
            ->add('check','text', array('required' => false, 'mapped' => false, 'attr' => array('style' => 'position: absolute; left: -100%; top: -100%;'), 'constraints' => array(new Blank())))
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
