<?php

namespace App\Tests\unit\MessageHandler;

use App\Message\PersistEntityMessage;
use App\MessageHandler\PersistEntityMessageHandler;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class PersistsEntityMessageHandlerTest extends TestCase
{

    public function test__invoke(): void
    {
        $object = new stdClass();

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())
            ->method('persist')
            ->with(self::equalTo($object));
        $entityManager->expects(self::once())
            ->method('flush');

        $event = new PersistEntityMessage($object);

        $handler = new PersistEntityMessageHandler($entityManager);
        $handler->__invoke($event);

        self::assertEquals($object, $event->getObject());
    }
}
