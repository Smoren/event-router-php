<?php

namespace Smoren\EventRouter\Interfaces;

/**
 * Interface for route rule config
 */
interface EventConfigInterface
{
    /**
     * Getter for "origin" property
     * @return string
     */
    public function getOrigin(): string;

    /**
     * Getter for "name" property
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Getter for "recipients" property
     * @return string[]|null
     */
    public function getRecipients(): ?array;

    /**
     * Getter for "extraFilter" property
     * @return callable|null
     */
    public function getExtraFilter(): ?callable;
}
