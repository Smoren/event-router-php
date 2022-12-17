<?php

namespace Smoren\EventRouter\Structs;

use Smoren\EventRouter\Interfaces\EventConfigInterface;
use Smoren\EventRouter\Interfaces\EventRouteRuleInterface;

/**
 * EventRouteRule class
 */
class EventRouteRule implements EventRouteRuleInterface
{
    /**
     * @var EventConfigInterface event route condition
     */
    protected EventConfigInterface $config;
    /**
     * @var callable event handler
     */
    protected $handler;

    /**
     * EventRouteRule constructor
     * @param EventConfigInterface $config
     * @param callable $handler
     */
    public function __construct(EventConfigInterface $config, callable $handler)
    {
        $this->config = $config;
        $this->handler = $handler;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig(): EventConfigInterface
    {
        return $this->config;
    }

    /**
     * {@inheritDoc}
     */
    public function getHandler(): callable
    {
        return $this->handler;
    }
}
