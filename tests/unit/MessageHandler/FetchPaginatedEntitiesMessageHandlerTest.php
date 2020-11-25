<?php

namespace App\Tests\unit\MessageHandler;

use App\Message\FetchPaginatedEntitiesMessage;
use App\MessageHandler\FetchPaginatedEntitiesMessageHandler;
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

        $paginator = $this->createMock(ApiPaginatorInterface::class);
        $paginator->expects(self::once())
            ->method('getPaginated')
            ->with(
                self::equalTo(self::PAGE),
                self::equalTo(self::PER_PAGE),
                self::equalTo(self::CLASSNAME),
            )
            ->willReturn($paginated);

        $event = new FetchPaginatedEntitiesMessage(self::PAGE, self::PER_PAGE, self::CLASSNAME);

        $handler = new FetchPaginatedEntitiesMessageHandler($paginator);
        $handler->__invoke($event);

        self::assertEquals(self::PAGE, $event->getPage());
        self::assertEquals(self::PER_PAGE, $event->getPerPage());
        self::assertEquals(self::CLASSNAME, $event->getClassName());
        self::assertEquals(self::TOTAL, $event->getTotal());
        self::assertEquals($items, $event->getResults());
    }
}
