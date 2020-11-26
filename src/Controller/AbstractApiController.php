<?php

namespace App\Controller;

use App\Message\FetchEntityMessage;
use App\Message\FetchPaginatedEntitiesInterface;
use App\Message\FetchPaginatedEntitiesMessage;
use App\Message\PersistEntityMessage;
use App\Message\RemoveEntityMessage;
use App\Service\ApiSerializerInterface;
use App\Service\ApiValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    public function indexAction(Request $request): JsonResponse
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

    public function getAction(int $id): JsonResponse
    {
        try {
            $entity = $this->getEntity($id);
        } catch (NotFoundHttpException $exception) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($this->serializer->toJson($entity, ['get']), Response::HTTP_OK, [], true);
    }

    public function postAction(Request $request, ApiValidatorInterface $apiValidator): JsonResponse
    {
        $className = $this->getApiEntityClassName();
        $entity    = new $className();

        $errorResponse = $this->fillEntityWithRequestData($entity, $request, $apiValidator);

        if ($errorResponse instanceof JsonResponse) {
            return $errorResponse;
        }

        $event = new PersistEntityMessage($entity);
        $this->messageBus->dispatch($event);

        return new JsonResponse($this->serializer->toJson($entity, ['all']), Response::HTTP_CREATED, [], true);
    }

    public function putAction(int $id, Request $request, ApiValidatorInterface $apiValidator): JsonResponse
    {
        try {
            $entity = $this->getEntity($id);
        } catch (NotFoundHttpException $exception) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $className        = $this->getApiEntityClassName();
        $empty            = new $className();
        $emptyObjectError = $this->fillEntityWithRequestData($empty, $request, $apiValidator);
        if ($emptyObjectError instanceof JsonResponse) {
            return $emptyObjectError;
        }

        $errorResponse = $this->fillEntityWithRequestData($entity, $request, $apiValidator);
        if ($errorResponse instanceof JsonResponse) {
            return $errorResponse;
        }

        $event = new PersistEntityMessage($entity);
        $this->messageBus->dispatch($event);

        return new JsonResponse($this->serializer->toJson($entity, ['update']), Response::HTTP_OK, [], true);
    }

    public function patchAction(int $id, Request $request, ApiValidatorInterface $apiValidator): JsonResponse
    {
        try {
            $entity = $this->getEntity($id);
        } catch (NotFoundHttpException $exception) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        $errorResponse = $this->fillEntityWithRequestData($entity, $request, $apiValidator);

        if ($errorResponse instanceof JsonResponse) {
            return $errorResponse;
        }

        $event = new PersistEntityMessage($entity);
        $this->messageBus->dispatch($event);

        return new JsonResponse($this->serializer->toJson($entity, ['update']), Response::HTTP_OK, [], true);
    }

    public function deleteAction(int $id): JsonResponse
    {
        try {
            $entity = $this->getEntity($id);
        } catch (NotFoundHttpException $exception) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $event = new RemoveEntityMessage($entity);
        $this->messageBus->dispatch($event);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    protected function paginatedResult(FetchPaginatedEntitiesInterface $fetchPaginated, iterable $items, $groups = ['index']): string
    {
        return $this->serializer->toJson([
            'total'   => $fetchPaginated->getTotal(),
            'page'    => $fetchPaginated->getPage(),
            'perPage' => $fetchPaginated->getPerPage(),
            'items'   => $items
        ], $groups);
    }

    protected function getEntity(int $id): object
    {
        $message = new FetchEntityMessage($id, $this->getApiEntityClassName());
        $this->messageBus->dispatch($message);

        if ($message->getEntity() === null) {
            throw new NotFoundHttpException($message->getClassname());
        }

        return $message->getEntity();
    }

    protected function fillEntityWithRequestData(object $entity, Request $request, ApiValidatorInterface $apiValidator): ?JsonResponse
    {
        try {
            $this->serializer->jsonToObject($request->getContent(), $entity);
        } catch (\Exception $e) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        try {
            $validationErrors = $apiValidator->validateObject($entity);
        } catch (\Exception $e) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        if ($validationErrors->count() > 0) {
            return new JsonResponse($apiValidator->errorsListToMessageArray($validationErrors), Response::HTTP_EXPECTATION_FAILED);
        }

        return null;
    }
}
