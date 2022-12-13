<?php

namespace Smoren\EventRouter\Structs;

use Smoren\EventRouter\Interfaces\EventConfigInterface;
use Closure;

class EventConfig implements EventConfigInterface
{
    /**
     * @var string
     */
    protected string $origin;
    /**
     * @var string|null
     */
    protected ?string $name;
    /**
     * @var string[]|null
     */
    protected ?array $recipients;
    /**
     * @var Closure|null
     */
    protected ?Closure $extraFilter;

    /**
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
    public function extraFilter(): ?callable
    {
        return $this->extraFilter();
    }
}
