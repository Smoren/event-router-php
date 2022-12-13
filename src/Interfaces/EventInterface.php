<?php

namespace Smoren\EventRouter\Interfaces;

interface EventInterface
{
    public function getOrigin(): string;

    public function getName(): string;

    public function getData(): array;

    public function toArray(): array;
}
