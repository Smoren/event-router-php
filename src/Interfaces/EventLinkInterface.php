<?php

namespace Smoren\EventRouter\Interfaces;

/**
 * Interface for linking extra data to event
 */
interface EventLinkInterface
{
    /**
     * Getter for "id" property
     * @return string
     */
    public function getId(): string;

    /**
     * Getter for "type" property
     * @return string
     */
    public function getType(): string;

    /**
     * Getter for "data" property
     * @return array<mixed>
     */
    public function getData(): array;
}
