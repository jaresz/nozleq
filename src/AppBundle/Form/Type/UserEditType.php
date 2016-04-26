<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UserEditType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username', TextType::class, array('label' => 'Login'))
        ->add('firstName', TextType::class, array('label' => 'Imię'))
        ->add('name', TextType::class, array('label' => 'Nazwisko'))
        ->add('email', TextType::class, array('label' => 'Email'))
        ->add('enabled', NULL, array('label' =>  'Konto włączone'))
        ->add('roAdmin', CheckboxType::class 	, array('label' =>  'Administrator', 'required' => false))
        ->add('roClerk', CheckboxType::class 	, array('label' =>  'Recepcjonista', 'required' => false))

        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'validation_groups' => array('admin_edit'),
        ));
    }
}
