<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType
{


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$translator = $this->get('translator');
        $builder
        ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
        ->add('plainPassword', RepeatedType::class, array(
            'type' => PasswordType::class,
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array('label' => 'form.password'),
            'second_options' => array('label' => 'form.password_confirmation'),
            'invalid_message' => 'fos_user.password.mismatch',
        ))
        ->add('email', EmailType::class, array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
        ->add('firstName', TextType::class, array('label' => 'form.first_name', 'translation_domain' => 'FOSUserBundle'))
        ->add('name', TextType::class, array('label' => 'form.last_name', 'translation_domain' => 'FOSUserBundle'))
        ->add('isAdmin', CheckboxType::class 	, array('label' =>  'form.is_admin', 'translation_domain' => 'FOSUserBundle', 'required' => false))
        ->add('isClerk', CheckboxType::class 	, array('label' =>  'form.is_clerk', 'translation_domain' => 'FOSUserBundle', 'required' => false))
        
        //->add('submit', SubmitType::class, array('label' => 'Zapisz'));
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'validation_groups' => array('registration', 'Strict'),
        ));
    }
}
