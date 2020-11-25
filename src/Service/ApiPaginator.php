<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class ApiPaginator implements ApiPaginatorInterface
{
    private PaginatorInterface              $paginator;
    private EntityManagerInterface          $entityManager;

    public function __construct(PaginatorInterface $paginator, EntityManagerInterface $entityManager)
    {
        $this->paginator     = $paginator;
        $this->entityManager = $entityManager;
    }

    public function getPaginated(int $page, int $perPage, string $className): PaginationInterface
    {
        /** @var EntityRepository $repo */
        $repo = $this->entityManager->getRepository($className);
        $qb   = $repo->createQueryBuilder('p');

        return $this->paginator->paginate($qb, $page, $perPage);
    }
}