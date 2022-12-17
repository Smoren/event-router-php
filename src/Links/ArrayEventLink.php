<?php

namespace Smoren\EventRouter\Links;

use Smoren\EventRouter\Interfaces\EventLinkInterface;

/**
 * ArrayEventLink class
 */
class ArrayEventLink implements EventLinkInterface
{
    /**
     * @var array<mixed> linked array
     */
    protected array $data;

    /**
     * ArrayEventLink constructor
     * @param array<mixed> $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): string
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return 'array';
    }

    /**
     * {@inheritDoc}
     */
    public function getData(): array
    {
        return $this->data;
    }
}
