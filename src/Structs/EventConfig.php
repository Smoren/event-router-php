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
     * @var Closure|null
     */
    protected ?Closure $extraFilter;

    /**
     * @param string $origin
     * @param string|null $name
     * @param callable|null $extraFilter
     */
    public function __construct(string $origin, ?string $name = null, ?callable $extraFilter = null)
    {
        $this->origin = $origin;
        $this->name = $name;
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

    public function extraFilter(): ?callable
    {
        return $this->extraFilter();
    }
}
