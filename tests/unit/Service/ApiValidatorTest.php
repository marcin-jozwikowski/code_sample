<?php

namespace App\Tests\Service;

use App\Service\ApiValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiValidatorTest extends TestCase
{
    /** @var MockObject|ValidatorInterface */
    private $validator;

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->createMock(ValidatorInterface::class);
    }

    public function testValidateObject(): void
    {
        $object = new \stdClass();

        $validationResult = new ConstraintViolationList();

        $this->validator->expects(self::once())
            ->method('validate')
            ->with(self::equalTo($object))
            ->willReturn($validationResult);

        $tested = new ApiValidator($this->validator);
        $result = $tested->validateObject($object);

        self::assertEquals($validationResult, $result);
    }

    public function testErrorsListToMessageArray(): void
    {
        $message = 'error';
        $path    = 'propertyPath';
        $error   = new ConstraintViolation($message, null, [], 'root', $path, 'value');

        $errors = new ConstraintViolationList();
        $errors->add($error);

        $tested = new ApiValidator($this->validator);
        $result = $tested->errorsListToMessageArray($errors);

        self::assertEquals([$path => [$message]], $result);
    }

    public function testEmptyErrorsListToMessageArray(): void
    {
        $errors = new ConstraintViolationList();

        $tested = new ApiValidator($this->validator);
        $result = $tested->errorsListToMessageArray($errors);

        self::assertEquals([], $result);
    }
}
