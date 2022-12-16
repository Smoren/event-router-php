<?php

namespace Smoren\EventRouter\Interfaces;

interface LoggerInterface
{
    /**
     * @param EventInterface $event
     * @return void
     */
    public function append(EventInterface $event): void;

    /**
     * @return EventInterface[]
     */
    public function getLog(): array;
}
