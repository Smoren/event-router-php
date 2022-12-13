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
     * @var array
     */
    protected array $recipients;

    /**
     * @param string $origin
     * @param string $name
     * @param string[] $recipients
     */
    public function __construct(string $origin, string $name, array $recipients = [])
    {
        $this->origin = $origin;
        $this->name = $name;
        $this->recipients = $recipients;
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

    /**
     * {@inheritDoc}
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'origin' => $this->getOrigin(),
            'name' => $this->getName(),
            'recipients' => $this->getRecipients(),
        ];
    }
}
