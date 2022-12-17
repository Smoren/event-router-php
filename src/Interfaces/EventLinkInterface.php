<?php

namespace Smoren\EventRouter\Interfaces;

interface EventLinkInterface
{
    /**
     * @return string|int
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return array
     */
    public function getData(): array;
}
