<?php

namespace App\Infrastructure\Plugin;


use App\Domain\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;

abstract class UserActionBase implements ActionInterface, ServiceSubscriberInterface
{

    /**
     * @var User[]
     */
    protected $users;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param User[] $users
     * @return UserActionBase
     */
    public function setUsers(array $users): UserActionBase
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedServices()
    {
        return [
            'doctrine' => ManagerRegistry::class
        ];
    }

}