<?php


namespace App\Message;


class FetchEntityQuery implements FetchEntityQueryInterface
{
    protected int     $id;
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
}