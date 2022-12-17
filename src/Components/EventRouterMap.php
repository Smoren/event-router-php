<?php

namespace Smoren\EventRouter\Components;

use Smoren\EventRouter\Interfaces\EventConfigInterface;
use Smoren\EventRouter\Interfaces\EventInterface;

/**
 * Map for storing event route rules
 */
class EventRouterMap
{
    /**
     * @var array<string, array<array{EventConfigInterface, callable}>>
     * [origin => [[config, handler], ...], ...]
     */
    protected array $originMap = [];
    /**
     * @var array<string, array<string, array<array{EventConfigInterface, callable}>>>
     * [origin => [name => [[config, handler], ...], ...], ...]
     */
    protected array $originNameMap = [];

    /**
     * Adds new event router rule to map
     * @param EventConfigInterface $config
     * @param callable $handler
     * @return void
     */
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
     * Returns suitable handlers from map for event
     * @param EventInterface $event
     * @return callable[]
     */
    public function get(EventInterface $event): array
    {
        $handlers = [];

        /**
         * @var EventConfigInterface $config
         * @var callable $handler
         */
        foreach($this->originMap[$event->getOrigin()] ?? [] as [$config, $handler]) {
            if(!$this->hasRecipientsIntersection($config, $event)) {
                continue;
            }
            if(!$this->applyExtraFilter($config, $event)) {
                continue;
            }
            $handlers[] = $handler;
        }

        foreach($this->originNameMap[$event->getOrigin()][$event->getName()] ?? [] as [$config, $handler]) {
            if(!$this->hasRecipientsIntersection($config, $event)) {
                continue;
            }
            if(!$this->applyExtraFilter($config, $event)) {
                continue;
            }
            $handlers[] = $handler;
        }

        return $handlers;
    }

    /**
     * Returns true if config condition and event have intersection by recipients
     * @param EventConfigInterface $config route config condition
     * @param EventInterface $event event
     * @return bool
     */
    protected function hasRecipientsIntersection(EventConfigInterface $config, EventInterface $event): bool
    {
        $candidates = $config->getRecipients();

        if($candidates === null) {
            return true;
        }

        $recipients = $event->getRecipients();

        return (bool)count(array_intersect($candidates, $recipients));
    }

    /**
     * Returns result of extra filter call for event
     * @param EventConfigInterface $config route config condition
     * @param EventInterface $event event
     * @return bool
     */
    protected function applyExtraFilter(EventConfigInterface $config, EventInterface $event): bool
    {
        return ($filter = $config->getExtraFilter()) === null || $filter($event);
    }
}
