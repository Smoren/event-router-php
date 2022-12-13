<?php

namespace Smoren\EventRouter\Interfaces;

interface EventInterface
{
    /**
     * @return string
     */
    public function getOrigin(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return string[]
     */
    public function getRecipients(): array;

    /**
     * @return array
     */
    public function toArray(): array;
}
