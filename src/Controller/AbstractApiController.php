<?php

namespace App\Controller;

use App\Message\FetchPaginatedEntitiesInterface;
use App\Message\FetchPaginatedEntitiesMessage;
use App\Service\ApiSerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class AbstractApiController extends AbstractController
{
    protected ApiSerializerInterface $serializer;
    protected MessageBusInterface    $messageBus;

    abstract protected function getApiEntityClassName(): string;

    public function __construct(ApiSerializerInterface $serializer, MessageBusInterface $messageBus)
    {
        $this->serializer = $serializer;
        $this->messageBus = $messageBus;
    }

    public function index(Request $request): JsonResponse
    {
        $fetchPaginated = new FetchPaginatedEntitiesMessage(
            (int)$request->query->get('page', '1'),
            (int)$request->query->get('perPage', '3'),
            $this->getApiEntityClassName(),
        );
        $this->messageBus->dispatch($fetchPaginated);
        $result = $this->paginatedResult($fetchPaginated, $fetchPaginated->getResults());
        return new JsonResponse($result, Response::HTTP_OK, [], true);
    }

    protected function paginatedResult(FetchPaginatedEntitiesInterface $fetchPaginated, iterable $items): string
    {
        return $this->serializer->toJson([
            'total'   => $fetchPaginated->getTotal(),
            'page'    => $fetchPaginated->getPage(),
            'perPage' => $fetchPaginated->getPerPage(),
            'items'   => $items
        ]);
    }
}
