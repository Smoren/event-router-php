<?php

namespace Smoren\EventRouter\Components;

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
            $result = $handler($event);

            if($result instanceof EventInterface) {
                $this->handle($result);
            } elseif(is_array($result)) {
                foreach($result as $item) {
                    if($item instanceof EventInterface) {
                        $this->handle($item);
                    }
                }
            }
        }
    }
}
