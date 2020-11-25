<?php


namespace App\Message;


interface FetchEntityMessageInterface
{
    public function getClassname(): string;

    public function getId(): int;

    public function setEntity(?object $entity): void;

    public function getEntity(): ?object;
}