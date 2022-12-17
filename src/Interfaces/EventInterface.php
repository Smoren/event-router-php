<?php

namespace Smoren\EventRouter\Interfaces;

/**
 * Interface for event
 */
interface EventInterface
{
    /**
     * Getter for "origin" property
     * @return string
     */
    public function getOrigin(): string;

    /**
     * Getter for "name" property
     * @return string
     */
    public function getName(): string;

    /**
     * Getter for "recipients" property
     * @return array<mixed>
     */
    public function getRecipients(): array;

    /**
     * Getter for "data" property
     * @return array<mixed>
     */
    public function getData(): array;

    /**
     * Getter for "link" property
     * @return EventLinkInterface
     */
    public function getLink(): EventLinkInterface;

    /**
     * Array conversion method
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
