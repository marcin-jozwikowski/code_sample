<?php

namespace App\Tests\unit\MessageHandler;

use App\Message\FetchEntityQuery;
use App\MessageHandler\FetchEntityQueryHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use stdClass;

class FetchEntityMessageHandlerTest extends TestCase
{
    public const ENTITY_ID = 123;
    public const CLASSNAME = 'any';

    public function test__invoke(): void
    {
        $entity = new stdClass();
        $event  = new FetchEntityQuery(self::ENTITY_ID, self::CLASSNAME);

        $repo = $this->createMock(EntityRepository::class);
        $repo->expects(self::once())
            ->method('find')
            ->with(self::equalTo(self::ENTITY_ID))
            ->willReturn($entity);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())
            ->method('getRepository')
            ->with(self::equalTo(self::CLASSNAME))
            ->willReturn($repo);

        $tested = new FetchEntityQueryHandler($entityManager);
        $result = $tested->__invoke($event);

        self::assertEquals(self::ENTITY_ID, $event->getId());
        self::assertEquals(self::CLASSNAME, $event->getClassname());
        self::assertEquals($entity, $result);
    }
}
