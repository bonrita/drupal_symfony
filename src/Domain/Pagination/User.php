<?php

namespace App\Domain\Pagination;


use App\Application\Message\UserActionCommand;
use App\Application\User\Action;
use App\Infrastructure\Manager\Plugin\ActionManager;
use App\Infrastructure\Form\Helper\UserCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use App\Domain\Entity\User as EntityUser;
use Symfony\Component\Form\Form;
use App\Infrastructure\Form\Helper\User as HelperUser;
use Symfony\Component\Messenger\MessageBusInterface;


class User extends Pagination
{

    public function getAll(): PaginationInterface
    {
        return $this->getPaginator($this->getRepository()->getAll());
    }

    public function filter(Form $form): PaginationInterface
    {
        $builder = null;

        /** @var UserCollection $data */
        $data = $form->getData();

        if (!empty($data->name)) {
            $builder = $this->getRepository()->loadUsersContainNameOrEmail($data->name, $builder);
        }

        if (is_numeric($data->status)) {
            $builder = $this->getRepository()->loadUsersByStatus($data->status, $builder);
        }

        if (!empty($data->role)) {
            $builder = $this->getRepository()->loadUsersByRole($data->role->getName(), $builder);
        }

        if (!empty($data->action)) {
            $users = array_filter(
                array_map(
                    function (HelperUser $helper) {
                        return $helper->getAction() ? $helper->getUser()->getId() : null;
                    },
                    $data->getUsers()->toArray()
                )
            );

            $action = new UserActionCommand($data->action->id, $users, 'user');
            $this->container->get(MessageBusInterface::class)->dispatch($action);

            $builder = $this->getRepository()->getBuilder(null);
        }

        return $this->getPaginator($builder->getQuery());
    }

    protected function getRepository(): ObjectRepository
    {
        return $this->container->get('doctrine')->getRepository(EntityUser::class);
    }

    public static function getSubscribedServices()
    {
        return array_merge(
            parent::getSubscribedServices(),
            [
                'doctrine' => ManagerRegistry::class,
                ActionManager::class => ActionManager::class,
                MessageBusInterface::class => MessageBusInterface::class,
            ]
        );
    }

}