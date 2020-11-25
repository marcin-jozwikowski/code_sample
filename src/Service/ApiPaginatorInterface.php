<?php


namespace App\Service;


use Knp\Component\Pager\Pagination\PaginationInterface;

interface ApiPaginatorInterface
{
    public function getPaginated(int $page, int $perPage, string $className): PaginationInterface;
}