<?php

namespace Smoren\EventRouter\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\EventRouter\Components\EventRouter;
use Smoren\EventRouter\Events\Event;
use Smoren\EventRouter\Exceptions\EventRouterException;
use Smoren\EventRouter\Interfaces\EventInterface;
use Smoren\EventRouter\Structs\EventConfig;
use Smoren\NestedAccessor\Factories\SilentNestedAccessorFactory;

class MainTest extends Unit
{
    /**
     * @return void
     * @throws EventRouterException
     */
    public function testSimple()
    {
        $data = [];
        $logsContainer = SilentNestedAccessorFactory::fromArray($data);

        $router = new EventRouter(10);
        $router
            ->on(new EventConfig('origin1', null), function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append('global', $event->getName());
                $logsContainer->append($event->getOrigin(), $event->getName());
            })
            ->on(new EventConfig('origin2', null), function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append('global', $event->getName());
                $logsContainer->append($event->getOrigin(), $event->getName());
            });

        $router->handle(new Event('origin1', 'first'));
        $this->assertEquals(['first'], $logsContainer->get('global'));
        $this->assertEquals(['first'], $logsContainer->get('origin1'));
        $this->assertEquals(null, $logsContainer->get('origin2'));

        $router->handle(new Event('origin1', 'second'));
        $this->assertEquals(['first', 'second'], $logsContainer->get('global'));
        $this->assertEquals(['first', 'second'], $logsContainer->get('origin1'));
        $this->assertEquals(null, $logsContainer->get('origin2'));

        $router->handle(new Event('origin2', 'third'));
        $this->assertEquals(['first', 'second', 'third'], $logsContainer->get('global'));
        $this->assertEquals(['first', 'second'], $logsContainer->get('origin1'));
        $this->assertEquals(['third'], $logsContainer->get('origin2'));
    }

    /**
     * @return void
     * @throws EventRouterException
     */
    public function testSimpleWithEventNames()
    {
        $data = [];
        $logsContainer = SilentNestedAccessorFactory::fromArray($data);

        $router = new EventRouter(10);
        $router
            ->on(new EventConfig('origin1', null), function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append('global', $event->getName());
            })
            ->on(new EventConfig('origin1', 'test'), function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append('test', $event->getName());
            });

        $router->handle(new Event('origin1', 'first'));
        $this->assertEquals(['first'], $logsContainer->get('global'));
        $this->assertEquals(null, $logsContainer->get('test'));

        $router->handle(new Event('origin1', 'second'));
        $this->assertEquals(['first', 'second'], $logsContainer->get('global'));
        $this->assertEquals(null, $logsContainer->get('test'));

        $router->handle(new Event('origin1', 'test'));
        $this->assertEquals(['first', 'second', 'test'], $logsContainer->get('global'));
        $this->assertEquals(['test'], $logsContainer->get('test'));
    }

    /**
     * @return void
     * @throws EventRouterException
     */
    public function testHandleRecursion()
    {
        $data = [];
        $logsContainer = SilentNestedAccessorFactory::fromArray($data);

        $router = new EventRouter(10);
        $router
            ->on(new EventConfig('origin1', null), function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append("origin1.global", $event->getName());
            })
            ->on(new EventConfig('origin1', 'recursive_single'), function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append('origin1.recursive', $event->getName());
                return new Event('origin2', 'test');
            })
            ->on(new EventConfig('origin1', 'recursive_multiple'), function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append('origin1.recursive', $event->getName());
                return [
                    new Event('origin1', 'recursive_single'),
                    new Event('origin2', 'test'),
                ];
            })
            ->on(new EventConfig('origin2', null), function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append('origin2.global', $event->getName());
            });

        $router->handle(new Event('origin1', 'first'));
        $this->assertEquals([
            'origin1' => [
                'global' => ['first'],
            ],
        ], $logsContainer->get());

        $router->handle(new Event('origin1', 'recursive_single'));
        $this->assertEquals([
            'origin1' => [
                'global' => ['first', 'recursive_single'],
                'recursive' => ['recursive_single'],
            ],
            'origin2' => [
                'global' => ['test'],
            ]
        ], $logsContainer->get());

        $router->handle(new Event('origin1', 'recursive_multiple'));
        $this->assertEquals([
            'origin1' => [
                'global' => ['first', 'recursive_single', 'recursive_multiple', 'recursive_single'],
                'recursive' => ['recursive_single', 'recursive_multiple', 'recursive_single'],
            ],
            'origin2' => [
                'global' => ['test', 'test', 'test'],
            ],
        ], $logsContainer->get());
    }

    /**
     * @return void
     * @throws EventRouterException
     */
    public function testUnregisteredEvent()
    {
        $flag = false;

        $router = new EventRouter(10);
        $router
            ->on(new EventConfig('origin1', null), function(EventInterface $event) use (&$flag) {
                $flag = true;
            })
            ->on(new EventConfig('origin2', 'test'), function(EventInterface $event) use (&$flag) {
                $flag = true;
            });

        $router->handle(new Event('origin2', 'non_test'));
        $this->assertFalse($flag);

        $router->handle(new Event('origin3', 'test'));
        $this->assertFalse($flag);

        $router->handle(new Event('origin2', 'test'));
        $this->assertTrue($flag);
    }

    public function testSimpleLoop()
    {
        $log = [];
        $router = new EventRouter(3);
        $router
            ->on(new EventConfig('origin1', null), function(EventInterface $event) use (&$flag, &$log) {
                $log[] = $event->getName();
                return new Event('origin1', 'sub_event1');
            })
            ->on(new EventConfig('origin2', null), function(EventInterface $event) use (&$flag, &$log) {
                $log[] = $event->getName();
                return [
                    new Event('origin2', 'sub_event2'),
                    new Event('origin2', 'sub_event3'),
                ];
            });

        try {
            $router->handle(new Event('origin1', 'main_event'));
            $this->fail();
        } catch(EventRouterException $e) {
            $this->assertEquals(3, $e->getData()['max_depth_level_count']);
            $this->assertEquals(['main_event', 'sub_event1', 'sub_event1'], $log);
        }

        $log = [];
        try {
            $router->handle(new Event('origin2', 'main_event'));
            $this->fail();
        } catch(EventRouterException $e) {
            $this->assertEquals(3, $e->getData()['max_depth_level_count']);
            $this->assertEquals(['main_event', 'sub_event2', 'sub_event2'], $log);
        }
    }

    public function testComplicatedLoop()
    {
        $log = [];
        $router = new EventRouter(5);
        $router
            ->on(new EventConfig('origin1', null), function(EventInterface $event) use (&$log) {
                $log[] = $event->getName();
                return new Event('origin2', 'sub_event1');
            })
            ->on(new EventConfig('origin2', null), function(EventInterface $event) use (&$log) {
                $log[] = $event->getName();
                return [
                    new Event('origin1', 'sub_event2'),
                    new Event('origin1', 'sub_event3'),
                ];
            });

        try {
            $router->handle(new Event('origin1', 'main_event'));
            $this->fail();
        } catch(EventRouterException $e) {
            $this->assertEquals(5, $e->getData()['max_depth_level_count']);
            $this->assertEquals(['main_event', 'sub_event1', 'sub_event2', 'sub_event1', 'sub_event2'], $log);
        }
    }
}
