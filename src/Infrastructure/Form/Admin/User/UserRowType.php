<?php

namespace App\Infrastructure\Form\Admin\User;


use App\Infrastructure\Form\Field\LinkType;
use App\Infrastructure\Form\Field\ListType;
use App\Infrastructure\Form\Field\MarkupType;
use App\Infrastructure\Form\Helper\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRowType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'action',
            CheckboxType::class,
            [
                'label' => false,
                'required' => false,
            ]
        )->add(
            'username',
            LinkType::class,
            [
                'label' => false,
                'attr' => [
                    'class' => 'link-s'
                ]
            ]
        )->add(
            'status',
            MarkupType::class,
            [
                'label' => false,
            ]
        )->add(
            'roles',
            ListType::class,
            [
                'label' => false,
            ]
        )->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }

}
