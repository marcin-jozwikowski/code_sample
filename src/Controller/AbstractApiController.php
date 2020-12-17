<?php

namespace App\Controller;

use App\Service\ApiCRUDServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractApiController extends AbstractController
{
    private ApiCRUDServiceInterface $crudService;

    abstract protected function getApiEntityClassName(): string;

    public function __construct(ApiCRUDServiceInterface $CRUDService)
    {
        $this->crudService = $CRUDService;
    }

    public function indexAction(Request $request): JsonResponse
    {
        return $this->crudService->readPaginated(
            (int)$request->query->get('page', '1'),
            (int)$request->query->get('perPage', '3'),
            $this->getApiEntityClassName(),
        );
    }

    public function getAction(int $id): JsonResponse
    {
        return $this->crudService->readSingle($this->getApiEntityClassName(), $id);
    }

    public function postAction(Request $request): JsonResponse
    {
        return $this->crudService->create($this->getApiEntityClassName(), $request->getContent());
    }

    public function putAction(int $id, Request $request): JsonResponse
    {
        return $this->crudService->update($this->getApiEntityClassName(), $id, $request->getContent());
    }

    public function patchAction(int $id, Request $request): JsonResponse
    {
        return $this->crudService->updatePartial($this->getApiEntityClassName(), $id, $request->getContent());
    }

    public function deleteAction(int $id): JsonResponse
    {
        return $this->crudService->delete($this->getApiEntityClassName(), $id);
    }
}
