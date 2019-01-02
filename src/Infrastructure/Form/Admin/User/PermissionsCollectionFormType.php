<?php

namespace App\Infrastructure\Form\Admin\User;


use App\Infrastructure\Form\Helper\PermissionCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PermissionsCollectionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add(
                'permissions',
                CollectionType::class,
                [
                    'entry_type' => PermissionsFormType::class,
                    'entry_options' => [
                        'attr' => [
                            'class' => 'item', // we want to use 'tr.item' as collection elements' selector
                        ],
                        'permission_list' => $options['data'],

                    ],
                    'allow_add' => false,
                    'allow_delete' => false,
                    'prototype' => false,
                    'required' => false,
                    'by_reference' => false,
                    'delete_empty' => false,
                    'attr' => [
                        'class' => 'table permissions',
                    ],

                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Save',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => PermissionCollection::class,
            ]
        );
    }

}