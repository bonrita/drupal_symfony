<?php

namespace App\Application\Message\Handler;


use App\Application\Message\UserActionCommand;
use App\Infrastructure\Manager\Plugin\ActionManager;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use App\Domain\Entity\User as EntityUser;


class User implements ServiceSubscriberInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(UserActionCommand $command)
    {
        $criteria = Criteria::create()
            ->where(
                Criteria::expr()
                    ->in('id', $command->getUsers())
            );
        $users = $this->container->get('doctrine')
            ->getRepository(EntityUser::class)
            ->matching($criteria)->getValues();

        $this->container->get(ActionManager::class)->getPlugin($command->getType(), $command->getActionId())
            ->setUsers($users)
            ->execute();
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedServices()
    {
        return [
            'doctrine' => '?'.ManagerRegistry::class,
            ActionManager::class => ActionManager::class,
        ];
    }


}