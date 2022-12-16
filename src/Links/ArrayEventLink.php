<?php

namespace Smoren\EventRouter\Links;

use Smoren\EventRouter\Interfaces\EventLinkInterface;

class ArrayEventLink implements EventLinkInterface
{
    /**
     * @var array
     */
    protected array $data;

    /**
     * {@inheritDoc}
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
    public function getClassName(): string
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
