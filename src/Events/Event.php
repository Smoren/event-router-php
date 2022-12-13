<?php

namespace Smoren\EventRouter\Events;

class Event extends BaseEvent
{
    protected array $data;

    public function __construct(string $origin, string $name, array $data = [])
    {
        parent::__construct($origin, $name);
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function toArray(): array
    {
        $result = parent::toArray();
        $result['data'] = $this->getData();

        return $result;
    }
}
