<?php


namespace App\MessageHandler;


use App\Message\RemoveEntityMessageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RemoveEntityMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(RemoveEntityMessageInterface $removeEntityMessage)
    {
        $this->entityManager->remove($removeEntityMessage->getObject());
        $this->entityManager->flush();
    }
}