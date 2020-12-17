<?php


namespace App\Message;


interface FetchPaginatedEntitiesQueryInterface
{
    public function getPage(): int;

    public function getPerPage(): int;

    public function getClassName(): string;

    public function setResults(iterable $results): void;

    public function getResults(): ?iterable;

    public function getTotal(): int;

    public function setTotal(int $total);
}