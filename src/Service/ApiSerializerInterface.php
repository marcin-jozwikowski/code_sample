<?php


namespace App\Service;


interface ApiSerializerInterface
{
    public function toJson($data, array $groups = []): string;

    public function jsonToObject(string $json, object $object): object;
}