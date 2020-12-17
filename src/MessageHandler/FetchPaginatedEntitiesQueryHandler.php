<?php


namespace App\MessageHandler;


use App\Message\FetchPaginatedEntitiesQueryInterface;
use App\Service\ApiPaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FetchPaginatedEntitiesQueryHandler implements MessageHandlerInterface
{
    private ApiPaginatorInterface $paginator;

    public function __construct(ApiPaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function __invoke(FetchPaginatedEntitiesQueryInterface $fetchPaginatedEntities): PaginationInterface
    {
        return $this->paginator->getPaginated(
            $fetchPaginatedEntities->getPage(),
            $fetchPaginatedEntities->getPerPage(),
            $fetchPaginatedEntities->getClassName()
        );
    }
}