<?php

namespace App\Infrastructure\Form\Admin\User;


use App\Domain\Entity\Role;
use App\Infrastructure\Form\Helper\Permission;
use Psr\Container\ContainerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyPath;

class PermissionsFormType extends AbstractType implements ServiceSubscriberInterface
{

    /**
     * @var ContainerInterface
     */
    private $locator;


    /**
     * Permission constructor.
     * @param ContainerInterface $locator
     */
    public function __construct(ContainerInterface $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $permission = $this->getPermission($builder, $options);

        if ($permission) {
            $roles = $this->locator->get('doctrine')->getRepository(Role::class)->findAll();

            /** @var Role $role */
            foreach ($roles as $role) {
                $builder
                    ->add(
                        $role->getRole(),
                        CheckboxType::class,
                        [
                            'label' => false,
                            'value' => $permission->getId(),
                            'attr' => [
                                'checked' => \in_array($permission->getId(), $role->getPermissions(), true),
                            ],
                        ]
                    );
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Permission::class,
                'permission_list' => [],
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedServices()
    {
        return [
            'doctrine' => ManagerRegistry::class,
        ];
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return Permission|null
     */
    protected function getPermission(FormBuilderInterface $builder, array $options): ?Permission
    {
        /** @var PropertyPath $propertyPath */
        $propertyPath = $builder->getPropertyPath();

        if ($propertyPath) {
            $index = $propertyPath->getElement(0);

            return $options['permission_list']->getPermissions()->get($index);
        }

        return null;
    }

}