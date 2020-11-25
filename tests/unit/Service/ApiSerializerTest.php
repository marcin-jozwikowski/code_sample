<?php

namespace App\Tests\unit\Service;

use App\Service\ApiSerializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiSerializerTest extends TestCase
{
    public function testJsonToObject()
    {
        $json   = '{json}';
        $object = new \stdClass();

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects(self::once())
            ->method('deserialize')
            ->with(self::equalTo($json),
                self::equalTo(get_class($object)),
                self::equalTo('json'),
                self::equalTo([AbstractNormalizer::OBJECT_TO_POPULATE => $object])
            )
            ->willReturn($object);

        $tested = new ApiSerializer($serializer);

        $result = $tested->jsonToObject($json, $object);

        self::assertEquals($object, $result);
    }

    public function testJsonToObjectException()
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects(self::once())
            ->method('deserialize')
            ->willThrowException(new NotEncodableValueException());

        $tested = new ApiSerializer($serializer);

        $this->expectException(NotEncodableValueException::class);
        $tested->jsonToObject('test', new \stdClass());
    }

    public function testToJson()
    {
        $object     = new \stdClass();
        $json       = 'test';
        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects($this->once())
            ->method('serialize')
            ->with(
                $this->equalTo($object),
                $this->equalTo('json')
            )
            ->willReturn($json);

        $tested = new ApiSerializer($serializer);

        $result = $tested->toJson($object);

        $this->assertEquals($json, $result);
    }

    public function testToJsonException()
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects($this->once())
            ->method('serialize')
            ->willThrowException(new NotEncodableValueException());

        $tested = new ApiSerializer($serializer);

        $this->expectException(NotEncodableValueException::class);
        $tested->toJson(new \stdClass());
    }

}
