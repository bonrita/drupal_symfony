<?php

namespace App\Infrastructure\Form\User;


use App\Domain\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RegisterType
 *
 * @package App\Infrastructure\Form\User
 */
class RegisterType extends AccountForm
{

    protected function actions(FormBuilderInterface $builder) {
        $builder->add('submit', SubmitType::class, [
          'label' => 'forms.register.submit.label',
          'translation_domain' => 'forms',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'show_password' => true,
        ));
    }

}