<?php


namespace App\Message;


class PersistEntityMessage implements PersistEntityMessageInterface
{
    protected object $object;

    public function __construct(object $object)
    {
        $this->object = $object;
    }

    public function getObject(): object
    {
        return $this->object;
    }
}