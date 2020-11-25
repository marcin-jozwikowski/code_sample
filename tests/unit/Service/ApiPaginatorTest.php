<?php

namespace App\Tests\unit\Service;

use App\Service\ApiPaginator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\TestCase;

class ApiPaginatorTest extends TestCase
{

    public function testGetPaginated()
    {
        $className = 'anyClassName';
        $page      = 2;
        $perPage   = 10;

        $queryBuilder = $this->createMock(QueryBuilder::class);

        $repo = $this->createMock(EntityRepository::class);
        $repo->expects(self::once())
            ->method('createQueryBuilder')
            ->willReturn($queryBuilder);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())
            ->method('getRepository')
            ->with(self::equalTo($className))
            ->willReturn($repo);

        $pagination = $this->createMock(PaginationInterface::class);

        $paginator = $this->createMock(PaginatorInterface::class);
        $paginator->expects(self::once())
            ->method('paginate')
            ->with(
                self::equalTo($queryBuilder),
                self::equalTo($page),
                self::equalTo($perPage),
            )
            ->willReturn($pagination);

        $tested = new ApiPaginator($paginator, $entityManager);

        $result = $tested->getPaginated($page, $perPage, $className);

        $this->assertEquals($pagination, $result);

    }
}
