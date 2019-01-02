<?php

namespace App\Infrastructure\Form\Admin\User;


use App\Brt\MachineBundle\Form\Type\MachineNameType;
use App\Brt\MachineBundle\Model\MachineName;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AddRoleFormType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            MachineNameType::class,
            [
                'label' => 'Machine name',
                'required' => true,
                'translation_domain' => 'forms',
                'help' => 'This value should only contain numerical and no spaces.',
                'attr' => [
                    'maxlength' => 20,
                    'size' => '30',
                ],
            ]
        )->add(
            'title',
            TextType::class,
            [
                'label' => 'Title',
                'required' => true,
                'translation_domain' => 'forms',
                'help' => 'The human readable name.',
                'attr' => [
                    'maxlength' => 20,
                    'size' => '30',
                ],
            ]
        )->add(
            'description',
            TextType::class,
            [
                'label' => 'Description',
                'required' => true,
                'translation_domain' => 'forms',
                'help' => 'The description.',
            ]
        )->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'Add role',
                'translation_domain' => 'forms',
            ]
        )->getForm();
    }


}