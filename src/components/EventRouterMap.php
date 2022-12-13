<?php

namespace Smoren\EventRouter\components;

use Smoren\EventRouter\Interfaces\EventConfigInterface;
use Smoren\EventRouter\Interfaces\EventInterface;

class EventRouterMap
{
    /**
     * @var array<string, array{EventConfigInterface, callable}>
     */
    protected array $originMap = [];
    /**
     * @var array<string, array<string, array{EventConfigInterface, callable}>>
     */
    protected array $originNameMap = [];

    public function add(EventConfigInterface $config, callable $handler): void
    {
        [$origin, $name] = [$config->getOrigin(), $config->getName()];

        if($name === null) {
            if(!isset($this->originMap[$origin])) {
                $this->originNameMap[$origin] = [];
            }
            $this->originMap[$origin][] = [$config, $handler];
        } else {
            if(!isset($this->originNameMap[$origin])) {
                $this->originNameMap[$origin] = [];
            }
            if(!isset($this->originNameMap[$origin][$name])) {
                $this->originNameMap[$origin][$name] = [];
            }
            $this->originNameMap[$origin][$name][] = [$config, $handler];
        }
    }

    /**
     * @param EventInterface $event
     * @return callable[]
     */
    public function get(EventInterface $event): array
    {
        $handlers = [];

        foreach($this->originMap[$event->getOrigin()] as [$config, $handler]) {
            if($this->hasRecipientsIntersection($config, $event)) {
                $handlers[] = $handler;
            }
        }

        foreach($this->originNameMap[$event->getOrigin()][$event->getName()] ?? [] as [$config, $handler]) {
            if($this->hasRecipientsIntersection($config, $event)) {
                $handlers[] = $handler;
            }
        }

        return $handlers;
    }

    protected function hasRecipientsIntersection(EventConfigInterface $config, EventInterface $event): bool
    {
        $candidates = $config->getRecipients();

        if($candidates === null) {
            return true;
        }

        $recipients = $event->getRecipients();

        return (bool)count(array_intersect($candidates, $recipients));
    }
}
