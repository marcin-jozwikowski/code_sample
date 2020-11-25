<?php


namespace App\MessageHandler;


use App\Message\FetchPaginatedEntitiesInterface;
use App\Service\ApiPaginatorInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FetchPaginatedEntitiesMessageHandler implements MessageHandlerInterface
{
    private ApiPaginatorInterface $paginator;

    public function __construct(ApiPaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function __invoke(FetchPaginatedEntitiesInterface $fetchPaginatedEntities)
    {
        $paginated = $this->paginator->getPaginated(
            $fetchPaginatedEntities->getPage(),
            $fetchPaginatedEntities->getPerPage(),
            $fetchPaginatedEntities->getClassName()
        );

        $fetchPaginatedEntities->setResults($paginated->getItems());
        $fetchPaginatedEntities->setTotal($paginated->getTotalItemCount());
    }
}