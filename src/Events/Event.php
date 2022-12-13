<?php

namespace Smoren\EventRouter\Events;

class Event extends BaseEvent
{
    /**
     * @var array
     */
    protected array $data;

    /**
     * @param string $origin
     * @param string $name
     * @param array $data
     * @param string[] $recipients
     */
    public function __construct(string $origin, string $name, array $data = [], array $recipients = [])
    {
        parent::__construct($origin, $name, $recipients);
        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * {@return array}
     */
    public function toArray(): array
    {
        $result = parent::toArray();
        $result['data'] = $this->getData();

        return $result;
    }
}
