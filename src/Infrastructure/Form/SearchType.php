<?php

namespace App\Infrastructure\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('keyword', TextType::class, [
            'label' => 'Search',
            'constraints' => [
                new NotBlank(),
                new Length(['min' => 3]),
            ],
        ])
            ->add('search', SubmitType::class, [
                'label' => 'forms.search',
                'translation_domain' => 'forms',
            ])
            ->getForm();
    }

}
