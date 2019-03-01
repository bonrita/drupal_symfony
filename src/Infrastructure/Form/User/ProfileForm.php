<?php

namespace App\Infrastructure\Form\User;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Domain\Entity\User;

/**
 * Class ProfileForm
 *
 * @package App\Infrastructure\Form\User
 */
class ProfileForm extends AccountForm
{

    /**
     * {@inheritdoc}
     */
    protected function actions(FormBuilderInterface $builder)
    {
        $builder->add('submit', SubmitType::class, [
          'label' => 'messages.form.save_label',
          'translation_domain' => 'messages',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => User::class,
          'show_password' => true,
        ));
    }

}
