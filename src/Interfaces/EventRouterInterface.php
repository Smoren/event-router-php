<?php

namespace Smoren\EventRouter\Interfaces;

use Smoren\EventRouter\Exceptions\EventRouterException;

/**
 * Interface for event router
 */
interface EventRouterInterface
{
    /**
     * Registers event routing rule
     * @param EventConfigInterface $config route condition
     * @param callable $handler event handler
     * @return $this
     */
    public function on(EventConfigInterface $config, callable $handler): self;

    /**
     * Registers event routing rule
     * @param EventRouteRuleInterface $routeRule routing rule
     * @return $this
     */
    public function register(EventRouteRuleInterface $routeRule): self;

    /**
     * Sends event to router
     * @param EventInterface $event event
     * @return $this
     * @throws EventRouterException if recursion limit exceeded
     */
    public function send(EventInterface $event): self;

    /**
     * Getter for handling log
     * @return EventInterface[]
     */
    public function getLog(): array;
}
