<?php

namespace Smoren\EventRouter\Events;

use Smoren\EventRouter\Interfaces\EventLinkInterface;

class LinkedEvent extends BaseEvent
{
    protected EventLinkInterface $link;

    /**
     * @param string $origin
     * @param string $name
     * @param EventLinkInterface $link
     * @param string[] $recipients
     */
    public function __construct(string $origin, string $name, EventLinkInterface $link, array $recipients = [])
    {
        parent::__construct($origin, $name, $recipients);
        $this->link = $link;
    }

    /**
     * {@inheritDoc}
     */
    public function getData(): array
    {
        return $this->link->getData();
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $result = parent::toArray();
        $result['data'] = $this->getData();

        return $result;
    }
}
