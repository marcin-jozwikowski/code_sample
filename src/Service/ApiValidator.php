<?php


namespace App\Service;


use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface as Validator;

class ApiValidator implements ApiValidatorInterface
{
    private Validator $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function validateObject(object $object): ConstraintViolationListInterface
    {
        return $this->validator->validate($object);
    }

    public function errorsListToMessageArray(ConstraintViolationListInterface $validationErrors): array
    {
        $result = [];
        $amount = $validationErrors->count();
        for ($index = 0; $index < $amount; $index++) {
            $error                               = $validationErrors->get($index);
            $result[$error->getPropertyPath()][] = $error->getMessage();
        }

        return $result;
    }
}