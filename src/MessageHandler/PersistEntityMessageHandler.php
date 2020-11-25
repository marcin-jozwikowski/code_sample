<?php


namespace App\MessageHandler;


use App\Message\PersistEntityMessageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class PersistEntityMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(PersistEntityMessageInterface $persistEntityMessage)
    {
        $this->entityManager->persist($persistEntityMessage->getObject());
        $this->entityManager->flush();
    }
}