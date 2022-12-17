<?php

namespace Smoren\EventRouter\Interfaces;

/**
 * Interface for event route rule
 */
interface EventRouteRuleInterface
{
    /**
     * Getter for "config" property
     * @return EventConfigInterface
     */
    public function getConfig(): EventConfigInterface;

    /**
     * Getter for "handler" property
     * @return callable
     */
    public function getHandler(): callable;
}
