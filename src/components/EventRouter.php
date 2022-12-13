<?php

namespace Smoren\EventRouter\components;

use Smoren\EventRouter\Interfaces\EventConfigInterface;
use Smoren\EventRouter\Interfaces\EventInterface;
use Smoren\EventRouter\Interfaces\EventRouterInterface;

class EventRouter implements EventRouterInterface
{
    public function on(EventConfigInterface $config, callable $handler): EventRouterInterface
    {
        // TODO: Implement on() method.
    }

    public function handle(EventInterface $event): void
    {
        // TODO: Implement trigger() method.
    }
}
