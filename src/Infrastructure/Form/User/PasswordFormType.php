<?php

namespace App\Infrastructure\Form\User;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class PasswordFormType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'plainPassword',
            RepeatedType::class,
            [
                'type' => PasswordType::class,
                'translation_domain' => 'forms',
                'first_options' => [
                    'label' => 'form.user.password.label',
                    'label_attr' => [
                        'class' => 'd-none'
                    ],
                    'attr' => [
                        'class' => 'col-6',
                        'placeholder' => 'form.user.password.label',
                    ],
                ],
                'second_options' => [
                    'label' => 'form.repeat.password.label',
                    'label_attr' => [
                        'class' => 'd-none'
                    ],
                    'attr' => [
                        'class' => 'col-6',
                        'placeholder' => 'form.repeat.password.label',
                    ],
                ],
                'invalid_message' => 'validators.user.password.no.match',
                'constraints' => [
                    new Length(
                        [
                            'min' => 5,
                            'minMessage' => 'validators.user.password.too.short',
                        ]
                    ),
                ],
            ]
        );

        $builder->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'forms.reset.password.submit.label',
                'translation_domain' => 'forms',
                'attr' => [
                    'class' => 'btn-info btn-rounded col-6',
                ]
            ]
        )->getForm();
    }

}
