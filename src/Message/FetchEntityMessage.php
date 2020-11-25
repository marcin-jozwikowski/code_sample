<?php


namespace App\Message;


class FetchEntityMessage implements FetchEntityMessageInterface
{
    protected int     $id;
    protected ?object $entity = null;
    protected string  $className;

    public function __construct(int $id, string $className)
    {
        $this->id        = $id;
        $this->className = $className;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getClassname(): string
    {
        return $this->className;
    }

    public function setEntity(?object $entity): void
    {
        $this->entity = $entity;
    }

    public function getEntity(): ?object
    {
        return $this->entity;
    }
}