<?php

namespace App\Brt\MachineBundle\Form\Type;


use App\Brt\MachineBundle\Transformer\ViewManipulate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MachineNameType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new ViewManipulate());
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'machine_name';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }

}