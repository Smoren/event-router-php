<?php

namespace Smoren\EventRouter\Interfaces;

use Smoren\EventRouter\Exceptions\EventRouterException;

interface EventRouterInterface
{
    /**
     * @param EventConfigInterface $config
     * @param callable $handler
     * @return $this
     */
    public function on(EventConfigInterface $config, callable $handler): self;

    /**
     * @param EventInterface $event
     * @return $this
     * @throws EventRouterException
     */
    public function send(EventInterface $event): self;

    /**
     * @return EventInterface[]
     */
    public function getLog(): array;
}
