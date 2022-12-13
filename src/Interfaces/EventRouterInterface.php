<?php

namespace Smoren\EventRouter\Interfaces;

interface EventRouterInterface
{
    public function on(EventConfigInterface $config, callable $handler): self;

    public function handle(EventInterface $event): void;
}
