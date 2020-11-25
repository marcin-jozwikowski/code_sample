<?php


namespace App\Service;


interface ApiSerializerInterface
{
    public function toJson($data, array $fields = []): string;

    public function jsonToObject(string $json, object $object): object;
}