<?php

namespace App\Tests\unit\MessageHandler;

use App\Message\RemoveEntityMessage;
use App\MessageHandler\RemoveEntityMessageHandler;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class RemoveObjectMessageHandlerTest extends TestCase
{

    public function test__invoke(): void
    {
        $object = new stdClass();

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())
            ->method('remove')
            ->with(self::equalTo($object));
        $entityManager->expects(self::once())
            ->method('flush');

        $event = new RemoveEntityMessage($object);

        $handler = new RemoveEntityMessageHandler($entityManager);
        $handler->__invoke($event);

        self::assertEquals($object, $event->getObject());
    }
}
