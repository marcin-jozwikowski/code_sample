<?php

namespace App\Tests\unit\MessageHandler;

use App\Message\FetchPaginatedEntitiesQuery;
use App\MessageHandler\FetchPaginatedEntitiesQueryHandler;
use App\Service\ApiPaginatorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\Pagination\PaginationInterface;
use PHPUnit\Framework\TestCase;

class FetchPaginatedEntitiesMessageHandlerTest extends TestCase
{
    public const PAGE      = 1;
    public const PER_PAGE  = 10;
    public const TOTAL     = 20;
    public const CLASSNAME = 'classname';

    public function test__invoke(): void
    {
        $items = new ArrayCollection();

        $paginated = $this->createMock(PaginationInterface::class);
        $paginated->expects(self::once())
            ->method('getItems')
            ->willReturn($items);
        $paginated->expects(self::once())
            ->method('getTotalItemCount')
            ->willReturn(self::TOTAL);
        $paginated->expects(self::once())
            ->method('getCurrentPageNumber')
            ->willReturn(self::PAGE);
        $paginated->expects(self::once())
            ->method('getItemNumberPerPage')
            ->willReturn(self::PER_PAGE);

        $paginator = $this->createMock(ApiPaginatorInterface::class);
        $paginator->expects(self::once())
            ->method('getPaginated')
            ->with(
                self::equalTo(self::PAGE),
                self::equalTo(self::PER_PAGE),
                self::equalTo(self::CLASSNAME),
            )
            ->willReturn($paginated);

        $event = new FetchPaginatedEntitiesQuery(self::PAGE, self::PER_PAGE, self::CLASSNAME);

        $handler = new FetchPaginatedEntitiesQueryHandler($paginator);
        $result  = $handler->__invoke($event);

        self::assertEquals(self::PAGE, $result->getCurrentPageNumber());
        self::assertEquals(self::PER_PAGE, $result->getItemNumberPerPage());
        self::assertEquals(self::TOTAL, $result->getTotalItemCount());
        self::assertEquals($items, $result->getItems());
    }
}
