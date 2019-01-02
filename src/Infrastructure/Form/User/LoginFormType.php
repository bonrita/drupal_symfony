<?php

namespace App\Infrastructure\Form\User;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginFormType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            null
            ,
            [
                'label' => 'forms.email.label.address',
                'required' => true,
                'translation_domain' => 'forms',
                'help' => 'Enter your email address or username.',
            ]
        )->add(
            'password',
            PasswordType::class,
            array(
                'help' => 'Enter the password that accompanies your email address.',
                'translation_domain' => 'forms',
                'label' => 'form.user.password.label',
            )
        )->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'Log in',
                'translation_domain' => 'forms',
            ]
        )->getForm();
    }

}