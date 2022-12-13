<?php

namespace Smoren\EventRouter\components;

use Smoren\EventRouter\Interfaces\EventConfigInterface;
use Smoren\EventRouter\Interfaces\EventInterface;
use Smoren\EventRouter\Interfaces\EventRouterInterface;

class EventRouter implements EventRouterInterface
{
    /**
     * @var EventRouterMap
     */
    protected EventRouterMap $map;

    public function __construct()
    {
        $this->map = new EventRouterMap();
    }

    public function on(EventConfigInterface $config, callable $handler): EventRouterInterface
    {
        $this->map->add($config, $handler);
        return $this;
    }

    public function handle(EventInterface $event): void
    {
        foreach($this->map->get($event) as $handler) {
            $handler($event);
        }
    }
}
