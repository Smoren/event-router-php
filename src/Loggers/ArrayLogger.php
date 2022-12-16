<?php

namespace Smoren\EventRouter\Loggers;

use Smoren\EventRouter\Interfaces\EventInterface;
use Smoren\EventRouter\Interfaces\LoggerInterface;

class ArrayLogger implements LoggerInterface
{
    /**
     * @var EventInterface[]
     */
    protected array $log = [];

    /**
     * {@inheritDoc}
     */
    public function append(EventInterface $event): void
    {
        $this->log[] = $event;
    }

    /**
     * {@inheritDoc}
     */
    public function getLog(): array
    {
        return $this->log;
    }
}
