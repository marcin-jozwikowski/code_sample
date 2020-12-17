<?php


namespace App\MessageHandler;


use App\Message\FetchEntityQueryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FetchEntityQueryHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(FetchEntityQueryInterface $fetchEntityMessage): object
    {
        $repository = $this->entityManager->getRepository($fetchEntityMessage->getClassname());
        return $repository->find($fetchEntityMessage->getId());
    }
}