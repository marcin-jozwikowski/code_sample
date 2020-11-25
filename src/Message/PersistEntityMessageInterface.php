<?php


namespace App\Message;


interface PersistEntityMessageInterface
{
    public function getObject(): object;
}