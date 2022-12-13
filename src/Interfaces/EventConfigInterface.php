<?php

namespace Smoren\EventRouter\Interfaces;

interface EventConfigInterface
{
    public function getOrigin(): string;

    public function getName(): ?string;

    public function extraFilter(): ?callable;
}
