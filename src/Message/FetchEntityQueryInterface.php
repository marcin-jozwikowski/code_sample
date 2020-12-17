<?php


namespace App\Message;


interface FetchEntityQueryInterface
{
    public function getClassname(): string;

    public function getId(): int;
}