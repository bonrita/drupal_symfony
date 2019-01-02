<?php

namespace App\Infrastructure\Form\Admin\User;


use App\Infrastructure\Manager\Plugin\ActionManager;
use App\Domain\Entity\Role;
use App\Infrastructure\Form\Helper\UserCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Domain\Pagination\User as AppPagination;

class UserCollectionFormType extends AbstractType implements ServiceSubscriberInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder->setDataMapper($this);
        $roles = $this->container->get('doctrine')->getRepository(Role::class)->findAll();
        $actions = $this->container->get(ActionManager::class)->getPluginByType()['user'] ?? [];

        $builder->add(
            'name',
            TextType::class,
            [
                'label' => 'Name or email contains',
                'attr' => [
                    'maxsize' => 20,
                ],
                'required' => false,
            ]
        )->add(
            'status',
            ChoiceType::class,
            [
                'label' => 'Status',
                'choices' => [
                    '-- All --' => '',
                    'Active' => '1',
                    'Blocked' => '0',
                ],
                'required' => false,
            ]
        )->add(
            'role',
            ChoiceType::class,
            [
                'label' => 'Role',
                'choice_loader' => new CallbackChoiceLoader(
                    function () use ($roles) {
                        $options = ['-- All --' => 0];
                        foreach ($roles as $role) {
                            $options[$role->getTitle() ?: $role->getRole()] = $role;
                        }

                        return $options;
                    }
                ),
                'required' => false,
                'choice_value' => 'name'
            ]
        )->add(
            'filter',
            SubmitType::class,
            [
                'label' => 'Filter',
                'translation_domain' => 'forms',
//                'attr' => [
//                    'value' => 1,
//                ]
            ]
        )->add(
            'reset',
            SubmitType::class,
            [
                'label' => 'Reset',
                'translation_domain' => 'forms',
            ]
        )->add(
            'action',
            ChoiceType::class,
            [
                'label' => 'Action',
                'choice_loader' => new CallbackChoiceLoader(
                    function () use ($actions) {
                        $options = ['-- All --' => ''];
                        foreach ($actions as $action) {
                            $options[$action->label] = $action;
                        }

                        return $options;
                    }
                ),
                'required' => false,
                'choice_value' => 'id',
//                'choice_label' => 'id'
            ]
        )->add(
            'execute_action',
            SubmitType::class,
            [
                'label' => 'Apply to selected items',
                'translation_domain' => 'forms',
//                'attr' => [
//                    'value' => 1,
//                ]
            ]
        )->add(
            'users',
            CollectionType::class,
            [
                'entry_type' => UserRowType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'by_reference' => false,
                'allow_add' => false,
                'allow_delete' => false,
            ]
        )->getForm();
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        if ($form->isSubmitted()) {
            /** @var SubmitButton $button */
            $button = $form->getClickedButton();
            $name = $button->getName();

        }

       $gg = 0;
    }

    /**
     * @inheritDoc
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $gg =0;
    }


    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults(
            [
                'data_class' => UserCollection::class,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function mapDataToForms($data, $forms)
    {

//        $users = [];
//        $userCollection = new UserCollection();
//        $forms = iterator_to_array($forms);
//        /** @var Form $status */
//        $status = $forms["status"];
//        $yy = $status->getData();
//        $sss = $status->getParent()->isSubmitted();
//        $pagination = $this->container->get(AppPagination::class)->getAll();
//        foreach ($pagination->getItems() as $user) {
//            $userWrapper = new User($user);
//            $userCollection->getUsers()->add($userWrapper);
//            $users[] = $userWrapper;
//        }
//        $forms['users']->setData($users);

        $gg =0;
    }

    /**
     * @inheritDoc
     */
    public function mapFormsToData($forms, &$data)
    {
//        $forms = iterator_to_array($forms);
//        $fff = $forms["status"]->getParent();
//        $userCollection = new UserCollection();
//        $pagination = $this->container->get(AppPagination::class)->getAll();
//        foreach ($pagination->getItems() as $user) {
//            $userWrapper = new User($user);
////            $data->getUsers()->add($userWrapper);
//            $userCollection->getUsers()->add($userWrapper);
////            $users[] = $userWrapper;
//            break;
//        }
//$data = $userCollection;

//        /** @var Form $ttt */
//        $ttt = $forms["users"];
//        $ttt->setData($userCollection);
//        $hh =0;
    }


    /**
     * @inheritDoc
     */
    public static function getSubscribedServices()
    {
        return array(
            'doctrine' => ManagerRegistry::class,
            ActionManager::class => ActionManager::class,
            AppPagination::class => AppPagination::class,
        );
    }

}