<?php

namespace App\Infrastructure\Form\User;


use App\Domain\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, [
            'label' => 'forms.email.label.address',
            'required' => true,
            'translation_domain' => 'forms',
            'help' => 'forms.register.email.help',
            'constraints' => [
                new Email([
                    'mode' => Email::VALIDATION_MODE_HTML5,
                    'message' => 'forms.email.validation.message',
                ])
            ],
        ])->add('username', TextType::class, [
            'label' => 'forms.username.label',
            'required' => true,
            'translation_domain' => 'forms',
            'help' => 'forms.username.help',
            'constraints' => [
                new Length(['min' => 4, 'max' => 8]),
            ],
        ]);

        if ($options['show_password']) {
            $builder->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'translation_domain' => 'forms',
                'first_options' => array('label' => 'form.user.password.label'),
                'second_options' => array('label' => 'form.repeat.password.label'),
            ));
        }

        $builder->add('submit', SubmitType::class, [
            'label' => 'forms.register.submit.label',
            'translation_domain' => 'forms',
        ])
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'show_password' => true,
        ));
    }

}