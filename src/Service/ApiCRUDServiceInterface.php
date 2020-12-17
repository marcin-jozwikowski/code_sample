<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\JsonResponse;

interface ApiCRUDServiceInterface
{
    public function readPaginated(int $page, int $perPage, string $className): JsonResponse;

    public function readSingle(string $entityClassName, int $id): JsonResponse;

    public function create(string $className, string $requestContent): JsonResponse;

    public function update(string $className, int $id, string $getContent): JsonResponse;

    public function updatePartial(string $className, int $id, string $requestContent): JsonResponse;

    public function delete(string $className, int $id): JsonResponse;
}