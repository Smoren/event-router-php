<?php

namespace Smoren\EventRouter\Components;

use Smoren\EventRouter\Interfaces\EventConfigInterface;
use Smoren\EventRouter\Interfaces\EventInterface;
use Smoren\EventRouter\Interfaces\EventRouterInterface;
use Smoren\EventRouter\Interfaces\EventRouteRuleInterface;
use Smoren\EventRouter\Interfaces\LoggerInterface;
use Smoren\EventRouter\Exceptions\EventRouterException;

/**
 * Event router
 */
class EventRouter implements EventRouterInterface
{
    /**
     * @var EventRouterMap map for storing event route rules
     */
    protected EventRouterMap $map;
    /**
     * @var int|null max recursion level of handling events
     */
    protected ?int $maxDepthLevelCount;
    /**
     * @var LoggerInterface logger
     */
    protected LoggerInterface $logger;

    /**
     * EventRouter constructor
     * @param int|null $maxDepthLevelCount
     * @param LoggerInterface $logger
     */
    public function __construct(?int $maxDepthLevelCount, LoggerInterface $logger)
    {
        $this->maxDepthLevelCount = $maxDepthLevelCount;
        $this->map = new EventRouterMap();
        $this->logger = $logger;
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
    public function register(EventRouteRuleInterface $routeRule): self
    {
        return $this->on($routeRule->getConfig(), $routeRule->getHandler());
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
     * {@inheritDoc}
     */
    public function getLog(): array
    {
        return $this->logger->getLog();
    }

    /**
     * Internal method for recursive event handling
     * @param EventInterface $event event to handle
     * @param int $depthLevelCount current recursion level
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

        foreach($this->map->get($event) as $handler) {
            $result = $handler($event);
            $this->logger->append($event);

            if($result instanceof EventInterface) {
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
