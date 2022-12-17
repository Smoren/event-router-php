<?php

namespace Smoren\EventRouter\Structs;

use Smoren\EventRouter\Interfaces\EventConfigInterface;

/**
 * EventConfig class
 */
class EventConfig implements EventConfigInterface
{
    /**
     * @var string event origin
     */
    protected string $origin;
    /**
     * @var string|null event name
     */
    protected ?string $name;
    /**
     * @var string[]|null event recipients
     */
    protected ?array $recipients;
    /**
     * @var callable|null extra filter
     */
    protected $extraFilter;

    /**
     * EventConfig constructor
     * @param string $origin
     * @param string|null $name
     * @param string[] $recipients
     * @param callable|null $extraFilter
     */
    public function __construct(
        string $origin,
        ?string $name = null,
        ?array $recipients = null,
        ?callable $extraFilter = null
    ) {
        $this->origin = $origin;
        $this->name = $name;
        $this->recipients = $recipients;
        $this->extraFilter = $extraFilter;
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getRecipients(): ?array
    {
        return $this->recipients;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtraFilter(): ?callable
    {
        return $this->extraFilter;
    }
}
