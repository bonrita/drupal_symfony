<?php

namespace App\Domain\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{

    /**
     * @inheritDoc
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function loadUsersContainNameOrEmail(string $keyword, QueryBuilder $builder = null): QueryBuilder
    {
        $builder = $this->getBuilder($builder);

        $builder
            ->andWhere('u.username LIKE :username OR u.email LIKE :email')
            ->setParameter('username', "%$keyword%")
            ->setParameter('email', "%$keyword%");

        return $builder;

    }

    public function getAll(): Query
    {
        return $this->createQueryBuilder('u')->getQuery();
    }

    public function loadUsersByStatus($status, QueryBuilder $builder = null): QueryBuilder
    {
        $builder = $this->getBuilder($builder);

        $builder->andWhere('u.isActive = :status')
            ->setParameter('status', $status);

        return $builder;
    }

    public function loadUsersByRole($roleId, QueryBuilder $builder = null): QueryBuilder
    {
        $builder = $this->getBuilder($builder);

        $builder->innerJoin('u.roles', 'r')
            ->andWhere('r.name = :role_id')
            ->setParameter('role_id', $roleId);

        return $builder;
    }

    public function getBuilder($builder): QueryBuilder
    {
        if ($builder === null) {
            $builder = $this->createQueryBuilder('u');
        }

        return $builder;
    }

}
