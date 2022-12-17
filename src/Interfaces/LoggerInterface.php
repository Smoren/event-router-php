<?php

namespace Smoren\EventRouter\Interfaces;

/**
 * Interface for logging events
 */
interface LoggerInterface
{
    /**
     * Adds event to log
     * @param EventInterface $event event
     * @return void
     */
    public function append(EventInterface $event): void;

    /**
     * Returns all logged events
     * @return EventInterface[]
     */
    public function getLog(): array;
}
