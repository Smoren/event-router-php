<?php

namespace Smoren\EventRouter\Interfaces;

interface EventRouteRuleInterface
{
    /**
     * @return EventConfigInterface
     */
    public function getConfig(): EventConfigInterface;

    /**
     * @return callable
     */
    public function getHandler(): callable;
}
