<?php

namespace Smoren\EventRouter\Interfaces;

interface EventConfigInterface
{
    /**
     * @return string
     */
    public function getOrigin(): string;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return string[]|null
     */
    public function getRecipients(): ?array;

    /**
     * @return callable|null
     */
    public function getExtraFilter(): ?callable;
}
