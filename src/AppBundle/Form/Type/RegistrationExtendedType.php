<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Rozszerza klasę RegistrationFormType z FOSUserBundle
 * o pola dodatkowe jak imię i nazwisko
 *
 * @author jkb
 *        
 */
class RegistrationExtendedType extends AbstractType
{

    /**
     * Dodaje dodatkowe pola do formularza
     *
     * {@inheritDoc}
     *
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class, [
            'label' => 'form.first_name',
            'translation_domain' => 'FOSUserBundle',
            'required' => true
        ]);
        $builder->add('lastName', TextType::class, [
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
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }


    public function getBlockPrefix()
    {
        return 'app_user_registration_extended';
    }
}
