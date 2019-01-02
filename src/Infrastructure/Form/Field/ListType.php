<?php

namespace App\Infrastructure\Form\Field;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'input' => 'list',
            ]
        );

        $resolver->setAllowedValues(
            'input',
            [
                'list',
            ]
        );

    }

}