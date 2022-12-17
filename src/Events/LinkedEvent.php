<?php

namespace Smoren\EventRouter\Events;

use Smoren\EventRouter\Interfaces\EventInterface;
use Smoren\EventRouter\Interfaces\EventLinkInterface;

/**
 * LinkedEvent class
 */
class LinkedEvent implements EventInterface
{
    /**
     * @var string origin
     */
    protected string $origin;
    /**
     * @var string name
     */
    protected string $name;
    /**
     * @var array<mixed> recipients
     */
    protected array $recipients;
    /**
     * @var EventLinkInterface link
     */
    protected EventLinkInterface $link;

    /**
     * LinkedEvent constructor
     * @param string $origin
     * @param string $name
     * @param EventLinkInterface $link
     * @param string[] $recipients
     */
    public function __construct(string $origin, string $name, EventLinkInterface $link, array $recipients = [])
    {
        $this->origin = $origin;
        $this->name = $name;
        $this->recipients = array_values($recipients);
        $this->link = $link;
    }

    /**
     * {@inheritDoc}
     */
    public function getOrigin(): string
    {
        return $this->origin;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * {@inheritDoc}
     */
    public function getData(): array
    {
        return $this->link->getData();
    }

    /**
     * {@inheritDoc}
     */
    public function getLink(): EventLinkInterface
    {
        return $this->link;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'origin' => $this->getOrigin(),
            'name' => $this->getName(),
            'recipients' => $this->getRecipients(),
            'data' => $this->getData(),
        ];
    }
}
