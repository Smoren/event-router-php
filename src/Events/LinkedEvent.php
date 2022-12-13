<?php

namespace Smoren\EventRouter\Events;

use Smoren\EventRouter\Interfaces\EventLinkInterface;

class LinkedEvent extends BaseEvent
{
    protected EventLinkInterface $link;

    public function __construct(string $origin, string $name, EventLinkInterface $link)
    {
        parent::__construct($origin, $name);
        $this->link = $link;
    }

    public function getData(): array
    {
        return $this->link->getData();
    }

    public function toArray(): array
    {
        $result = parent::toArray();
        $result['data'] = $this->getData();

        return $result;
    }
}
