<?php

namespace Smoren\EventRouter\Events;

use Smoren\EventRouter\Interfaces\EventInterface;

abstract class BaseEvent implements EventInterface
{
    /**
     * @var string
     */
    protected string $origin;
    /**
     * @var string
     */
    protected string $name;

    /**
     * @param string $origin
     * @param string $name
     */
    public function __construct(string $origin, string $name)
    {
        $this->origin = $origin;
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function getOrigin(): string
    {
        return $this->origin;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return [
            'origin' => $this->origin,
            'name' => $this->name,
        ];
    }
}
