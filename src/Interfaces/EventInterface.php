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
     * @return array<mixed>
     */
    public function getRecipients(): array;

    /**
     * @return array<mixed>
     */
    public function getData(): array;

    /**
     * @return EventLinkInterface
     */
    public function getLink(): EventLinkInterface;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
