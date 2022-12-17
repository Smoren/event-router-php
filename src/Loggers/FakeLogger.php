<?php

namespace Smoren\EventRouter\Loggers;

use Smoren\EventRouter\Interfaces\EventInterface;
use Smoren\EventRouter\Interfaces\LoggerInterface;

/**
 * FakeLogger class
 */
class FakeLogger implements LoggerInterface
{
    /**
     * {@inheritDoc}
     */
    public function append(EventInterface $event): void
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getLog(): array
    {
        error_log(static::class.'::getLog() always returns empty array');
        return [];
    }
}
