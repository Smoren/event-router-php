<?php

namespace Smoren\EventRouter\Loggers;

use Smoren\EventRouter\Interfaces\EventInterface;
use Smoren\EventRouter\Interfaces\LoggerInterface;

class NonLogger implements LoggerInterface
{
    public function append(EventInterface $event): void
    {
    }

    public function getLog(): array
    {
        error_log(static::class.'::getLog() always returns empty array');
        return [];
    }
}
