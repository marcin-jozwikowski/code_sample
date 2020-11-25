<?php


namespace App\MessageHandler;


use App\Message\FetchEntityMessageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FetchEntityMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(FetchEntityMessageInterface $fetchEntityMessage)
    {
        $repository = $this->entityManager->getRepository($fetchEntityMessage->getClassname());
        $entity     = $repository->find($fetchEntityMessage->getId());
        $fetchEntityMessage->setEntity($entity);
    }
}