<?php

namespace Smoren\EventRouter\Events;

use Smoren\EventRouter\Links\ArrayEventLink;

class Event extends LinkedEvent
{
    /**
     * @param string $origin
     * @param string $name
     * @param array<mixed> $data
     * @param string[] $recipients
     */
    public function __construct(string $origin, string $name, array $data = [], array $recipients = [])
    {
        parent::__construct($origin, $name, new ArrayEventLink($data), $recipients);
    }
}
