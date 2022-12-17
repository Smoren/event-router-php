<?php

namespace Smoren\EventRouter\Interfaces;

interface EventLinkInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
