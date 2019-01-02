<?php

namespace App\Domain\Pagination;


use Doctrine\Common\Persistence\ObjectRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Pagination\PaginationInterface as KnpPaginationInterface;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class Pagination implements PaginationInterface, ServiceSubscriberInterface
{

    /**
     * @var int
     */
    protected $defaultPage = 1;

    /**
     * @var int
     */
    protected $limit = 5;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    abstract protected function getRepository(): ObjectRepository;

    protected function getPaginator(Query $query): KnpPaginationInterface
    {
        return $this->container->get('knp_paginator')->paginate(
            $query,
            $this->container->get('request_stack')->getCurrentRequest()->query->getInt('page', $this->defaultPage),
            $this->container->get('request_stack')->getCurrentRequest()->query->getInt('limit', $this->limit)
        );
    }

    /**
     * @param int $defaultPage
     *
     * @return Pagination
     */
    public function setDefaultPage(int $defaultPage): Pagination
    {
        $this->defaultPage = $defaultPage;

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return Pagination
     */
    public function setLimit(int $limit): Pagination
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedServices()
    {
        return array_merge(
            [
                'knp_paginator' => Paginator::class,
                'request_stack' => RequestStack::class,
            ]
        );
    }

}