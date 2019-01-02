<?php

namespace App\Infrastructure\Form\Helper;

use App\Domain\Entity\User as EntityUser;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class User
{



    /**
     * @var EntityUser
     */
    private $user;

    /**
     * @var bool
     */
    private $action = false;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(EntityUser $user, ContainerInterface $container)
    {
        $this->user = $user;
        $this->container = $container;
    }

    public function getAction(): bool
    {
        return $this->action;
    }

    public function getUsername(): array
    {
        return [
            'title' => $this->user->getUsername(),
            'href' => $this->container->get('router')->generate('user_page_user_view', [
                'user' => $this->user->getId()
            ]),
        ];
    }

    /**
     * @param mixed $action
     */
    public function setAction($action): void
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->user->isActive()? 'Active' : 'Blocked';
    }

    public function getRoles() {
        return array_map(function ($role){
            return $role->getTitle()? $role->getTitle() : $role->getRole();
        }, $this->user->getRoles());
    }

    /**
     * @return EntityUser
     */
    public function getUser(): EntityUser
    {
        return $this->user;
    }

}