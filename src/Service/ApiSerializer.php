<?php


namespace App\Service;


use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiSerializer implements ApiSerializerInterface
{
    protected SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function toJson($data, array $groups = []): string
    {
        return $this->serializer->serialize($data, JsonEncoder::FORMAT, !empty($groups) ? [AbstractNormalizer::GROUPS => $groups] : []);
    }

    public function jsonToObject(string $json, object $object): object
    {
        return $this->serializer->deserialize($json, get_class($object), JsonEncoder::FORMAT, [AbstractNormalizer::OBJECT_TO_POPULATE => $object]);
    }
}