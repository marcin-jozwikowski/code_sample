<?php


namespace App\Service;


use App\Message\FetchEntityQuery;
use App\Message\FetchPaginatedEntitiesQuery;
use App\Message\PersistEntityMessage;
use App\Message\RemoveEntityMessage;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class ApiCRUDService implements ApiCRUDServiceInterface
{
    protected ApiSerializerInterface $serializer;
    protected MessageBusInterface    $messageBus;
    protected ApiValidatorInterface  $apiValidator;

    public function __construct(ApiSerializerInterface $serializer, MessageBusInterface $messageBus, ApiValidatorInterface $apiValidator)
    {
        $this->serializer   = $serializer;
        $this->messageBus   = $messageBus;
        $this->apiValidator = $apiValidator;
    }

    public function readPaginated(int $page, int $perPage, string $className): JsonResponse
    {
        $fetchPaginated = new FetchPaginatedEntitiesQuery($page, $perPage, $className);

        $fetchPaginatedResponse = $this->messageBus->dispatch($fetchPaginated)->last(HandledStamp::class);
        if ($fetchPaginatedResponse === null) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }
        if (!$fetchPaginatedResponse->getResult() instanceof PaginationInterface) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        /** @var PaginationInterface $paginated */
        $paginated = $fetchPaginatedResponse->getResult();

        $result = $this->paginateResult($paginated, $paginated->getItems());
        return new JsonResponse($result, Response::HTTP_OK, [], true);
    }

    public function readSingle(string $entityClassName, int $id): JsonResponse
    {
        try {
            $entity = $this->getEntity($entityClassName, $id);
        } catch (NotFoundHttpException $exception) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($this->serializer->toJson($entity, ['get']), Response::HTTP_OK, [], true);
    }

    public function create(string $className, string $requestContent): JsonResponse
    {
        $entity = new $className();

        $errorResponse = $this->fillEntityWithRequestData($entity, $requestContent);

        if ($errorResponse instanceof JsonResponse) {
            return $errorResponse;
        }

        $event = new PersistEntityMessage($entity);
        $this->messageBus->dispatch($event);

        return new JsonResponse($this->serializer->toJson($entity, ['all']), Response::HTTP_CREATED, [], true);
    }

    public function update(string $className, int $id, string $getContent): JsonResponse
    {
        $empty            = new $className();
        $emptyObjectError = $this->fillEntityWithRequestData($empty, $getContent);
        if ($emptyObjectError instanceof JsonResponse) {
            return $emptyObjectError;
        }

        return $this->updatePartial($className, $id, $getContent);
    }

    public function updatePartial(string $className, int $id, string $requestContent): JsonResponse
    {
        try {
            $entity = $this->getEntity($className, $id);
        } catch (NotFoundHttpException $exception) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        $errorResponse = $this->fillEntityWithRequestData($entity, $requestContent);

        if ($errorResponse instanceof JsonResponse) {
            return $errorResponse;
        }

        $event = new PersistEntityMessage($entity);
        $this->messageBus->dispatch($event);

        return new JsonResponse($this->serializer->toJson($entity, ['update']), Response::HTTP_OK, [], true);
    }

    public function delete(string $className, int $id): JsonResponse
    {
        try {
            $entity = $this->getEntity($className, $id);
        } catch (NotFoundHttpException $exception) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $event = new RemoveEntityMessage($entity);
        $this->messageBus->dispatch($event);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    protected function fillEntityWithRequestData(object $entity, string $requestContent): ?JsonResponse
    {
        try {
            $this->serializer->jsonToObject($requestContent, $entity);
        } catch (\Exception $e) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        try {
            $validationErrors = $this->apiValidator->validateObject($entity);
        } catch (\Exception $e) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        if ($validationErrors->count() > 0) {
            return new JsonResponse($this->apiValidator->errorsListToMessageArray($validationErrors), Response::HTTP_EXPECTATION_FAILED);
        }

        return null;
    }

    protected function paginateResult(PaginationInterface $result, iterable $items, $groups = ['index']): string
    {
        return $this->serializer->toJson([
            'total'   => $result->getTotalItemCount(),
            'page'    => $result->getCurrentPageNumber(),
            'perPage' => $result->getItemNumberPerPage(),
            'items'   => $items
        ], $groups);
    }

    protected function getEntity(string $entityClassName, int $id): object
    {
        $message = new FetchEntityQuery($id, $entityClassName);
        /** @var HandledStamp $handled */
        $handled = $this->messageBus->dispatch($message)->last(HandledStamp::class);

        if ($handled->getResult() === null) {
            throw new NotFoundHttpException($message->getClassname());
        }

        return $handled->getResult();
    }
}