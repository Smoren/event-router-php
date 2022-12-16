<?php

namespace Smoren\EventRouter\Components;

use Smoren\EventRouter\Exceptions\EventRouterException;
use Smoren\EventRouter\Interfaces\EventConfigInterface;
use Smoren\EventRouter\Interfaces\EventInterface;
use Smoren\EventRouter\Interfaces\EventRouterInterface;

class EventRouter implements EventRouterInterface
{
    /**
     * @var EventRouterMap
     */
    protected EventRouterMap $map;
    /**
     * @var int|null
     */
    protected ?int $maxDepthLevelCount;

    /**
     * @param int|null $maxDepthLevelCount
     */
    public function __construct(?int $maxDepthLevelCount)
    {
        $this->maxDepthLevelCount = $maxDepthLevelCount;
        $this->map = new EventRouterMap();
    }

    /**
     * {@inheritDoc}
     */
    public function on(EventConfigInterface $config, callable $handler): self
    {
        $this->map->add($config, $handler);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function send(EventInterface $event): self
    {
        $this->_handle($event);
        return $this;
    }

    /**
     * @param EventInterface $event
     * @param int $depthLevelCount
     * @return void
     * @throws EventRouterException
     */
    protected function _handle(EventInterface $event, int $depthLevelCount = 0)
    {
        if($this->maxDepthLevelCount !== null && $depthLevelCount >= $this->maxDepthLevelCount) {
            throw new EventRouterException(
                'max depth level reached',
                EventRouterException::MAX_DEPTH_LEVEL_REACHED,
                null,
                ['max_depth_level_count' => $this->maxDepthLevelCount]
            );
        }

        $subEvents = [];

        foreach($this->map->get($event) as $handler) {
            $result = $handler($event);

            if($result instanceof EventInterface) {
                $subEvents[] = $result;
                $this->_handle($result, ++$depthLevelCount);
            } elseif(is_array($result)) {
                foreach($result as $item) {
                    if($item instanceof EventInterface) {
                        $this->_handle($item, ++$depthLevelCount);
                    }
                }
            }
        }
    }
}
