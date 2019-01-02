<?php

namespace App\Infrastructure\Form\Field;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkType extends AbstractType
{

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'input' => 'link',
//                'link' => [
//
//                ],
            ]
        );

        $resolver->setAllowedValues(
            'input',
            [
                'link',
            ]
        );

    }


}