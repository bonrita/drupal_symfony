<?php

namespace App\Domain\Pagination;


use Knp\Component\Pager\Pagination\PaginationInterface as KnpPaginationInterface;

interface PaginationInterface
{

    public function getAll(): KnpPaginationInterface;

}