<?php

namespace App\Infrastructure\Form\Field;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MarkupType extends AbstractType
{

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['value'] = $options['markup'];
    }


    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults(
            [
                'input' => 'markup',
                'markup' => [],
            ]
        );

        $resolver->setAllowedValues(
            'input',
            [
                'markup',
                'string',
            ]
        );

    }

}