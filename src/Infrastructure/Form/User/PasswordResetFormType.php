<?php

namespace App\Infrastructure\Form\User;


use App\Domain\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class PasswordResetFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'username',
            TextType::class,
            [
                'label' => 'forms.email_username.label',
                'required' => true,
                'translation_domain' => 'forms',
                'help' => 'forms.email_username.help',
                'attr' => [
                    'maxlength' => max(User::USERNAME_MAX_LENGTH, User::EMAIL_MAX_LENGTH),
                    'size' => 60,
                ],
                'constraints' => [
                    new Length(['min' => 4, 'max' => max(User::USERNAME_MAX_LENGTH, User::EMAIL_MAX_LENGTH)]),
                ],
            ]
        )->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'forms.password.submit.label',
                'translation_domain' => 'forms',
            ]
        )
            ->getForm();
    }


}