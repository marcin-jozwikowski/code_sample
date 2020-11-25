<?php


namespace App\Service;


use Symfony\Component\Validator\ConstraintViolationListInterface;

interface ApiValidatorInterface
{
    public function validateObject(object $object): ConstraintViolationListInterface;

    public function errorsListToMessageArray(ConstraintViolationListInterface $validationErrors): array;
}