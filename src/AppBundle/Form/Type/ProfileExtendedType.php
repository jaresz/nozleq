<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

/**
 * Klasa rozszerza klasę ProfileFormType z FOSUserBundle
 * o pola dodatkowe jak imię i nazwisko
 */
class ProfileExtendedType extends AbstractType
{

    /**
     * Dodaje dodatkowe pola do formularza
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('email');
        $builder->add('email', EmailType::class, array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'));
        $builder->add('firstName', TextType::class, [
            'label' => 'form.first_name',
            'translation_domain' => 'FOSUserBundle',
            'required' => true
        ]);
        $builder->add('name', TextType::class, [
            'label' => 'form.last_name',
            'translation_domain' => 'FOSUserBundle',
            'required' => true
        ]);
        $builder->add('phone', null, [
            'label' => 'form.phone',
            'translation_domain' => 'FOSUserBundle'
        ]);
    }

    /**
     * zwraca klasę fomrularza rodzica
     */
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile_extended';
    }
}
