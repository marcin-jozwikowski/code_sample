<?php


namespace App\Message;


class FetchPaginatedEntitiesMessage implements FetchPaginatedEntitiesInterface
{
    protected int       $page;
    protected int       $perPage;
    protected string    $className;
    protected int       $total   = 0;
    protected ?iterable $results = null;

    public function __construct(int $page, int $perPage, string $className)
    {
        $this->page      = $page;
        $this->perPage   = $perPage;
        $this->className = $className;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;
        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setResults(?iterable $results): void
    {
        $this->results = $results;
    }

    public function getResults(): ?iterable
    {
        return $this->results;
    }
}