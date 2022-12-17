<?php

namespace Smoren\EventRouter\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\EventRouter\Components\EventRouter;
use Smoren\EventRouter\Events\Event;
use Smoren\EventRouter\Exceptions\EventRouterException;
use Smoren\EventRouter\Interfaces\EventInterface;
use Smoren\EventRouter\Loggers\ArrayLogger;
use Smoren\EventRouter\Loggers\FakeLogger;
use Smoren\EventRouter\Structs\EventConfig;
use Smoren\EventRouter\Structs\EventRouteRule;
use Smoren\NestedAccessor\Factories\SilentNestedAccessorFactory;

class EventRouterTest extends Unit
{
    /**
     * @return void
     * @throws EventRouterException
     */
    public function testSimple()
    {
        $data = [];
        $logsContainer = SilentNestedAccessorFactory::fromArray($data);

        $router = new EventRouter(10, new ArrayLogger());
        $router
            ->on(new EventConfig('origin1', null), function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append('global', $event->getName());
                $logsContainer->append($event->getOrigin(), $event->getName());
            })
            ->on(new EventConfig('origin2', null), function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append('global', $event->getName());
                $logsContainer->append($event->getOrigin(), $event->getName());
            });

        $router->send(new Event('origin1', 'first'));
        $this->assertEquals(['first'], $logsContainer->get('global'));
        $this->assertEquals(['first'], $logsContainer->get('origin1'));
        $this->assertEquals(null, $logsContainer->get('origin2'));

        $router->send(new Event('origin1', 'second'));
        $this->assertEquals(['first', 'second'], $logsContainer->get('global'));
        $this->assertEquals(['first', 'second'], $logsContainer->get('origin1'));
        $this->assertEquals(null, $logsContainer->get('origin2'));

        $router->send(new Event('origin2', 'third'));
        $this->assertEquals(['first', 'second', 'third'], $logsContainer->get('global'));
        $this->assertEquals(['first', 'second'], $logsContainer->get('origin1'));
        $this->assertEquals(['third'], $logsContainer->get('origin2'));
    }


    /**
     * @return void
     * @throws EventRouterException
     */
    public function testSimpleRegister()
    {
        $data = [];
        $logsContainer = SilentNestedAccessorFactory::fromArray($data);

        $rule1 = new EventRouteRule(
            new EventConfig('origin1', null),
            function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append('global', $event->getName());
                $logsContainer->append($event->getOrigin(), $event->getName());
            }
        );
        $rule2 = new EventRouteRule(
            new EventConfig('origin2', null),
            function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append('global', $event->getName());
                $logsContainer->append($event->getOrigin(), $event->getName());
            }
        );

        $router = new EventRouter(10, new ArrayLogger());
        $router
            ->register($rule1)
            ->register($rule2);

        $router->send(new Event('origin1', 'first'));
        $this->assertEquals(['first'], $logsContainer->get('global'));
        $this->assertEquals(['first'], $logsContainer->get('origin1'));
        $this->assertEquals(null, $logsContainer->get('origin2'));

        $router->send(new Event('origin1', 'second'));
        $this->assertEquals(['first', 'second'], $logsContainer->get('global'));
        $this->assertEquals(['first', 'second'], $logsContainer->get('origin1'));
        $this->assertEquals(null, $logsContainer->get('origin2'));

        $router->send(new Event('origin2', 'third'));
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

        $router = new EventRouter(10, new ArrayLogger());
        $router
            ->on(new EventConfig('origin1', null), function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append('global', $event->getName());
            })
            ->on(new EventConfig('origin1', 'test'), function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append('test', $event->getName());
            });

        $router->send(new Event('origin1', 'first'));
        $this->assertEquals(['first'], $logsContainer->get('global'));
        $this->assertEquals(null, $logsContainer->get('test'));

        $router->send(new Event('origin1', 'second'));
        $this->assertEquals(['first', 'second'], $logsContainer->get('global'));
        $this->assertEquals(null, $logsContainer->get('test'));

        $router->send(new Event('origin1', 'test'));
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

        $router = new EventRouter(10, new ArrayLogger());
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

        $router->send(new Event('origin1', 'first'));
        $this->assertEquals([
            'origin1' => [
                'global' => ['first'],
            ],
        ], $logsContainer->get(null));

        $router->send(new Event('origin1', 'recursive_single'));
        $this->assertEquals([
            'origin1' => [
                'global' => ['first', 'recursive_single'],
                'recursive' => ['recursive_single'],
            ],
            'origin2' => [
                'global' => ['test'],
            ]
        ], $logsContainer->get(null));

        $router->send(new Event('origin1', 'recursive_multiple'));
        $this->assertEquals([
            'origin1' => [
                'global' => ['first', 'recursive_single', 'recursive_multiple', 'recursive_single'],
                'recursive' => ['recursive_single', 'recursive_multiple', 'recursive_single'],
            ],
            'origin2' => [
                'global' => ['test', 'test', 'test'],
            ],
        ], $logsContainer->get(null));
    }

    /**
     * @return void
     * @throws EventRouterException
     */
    public function testUnregisteredEvent()
    {
        $flag = false;

        $router = new EventRouter(10, new ArrayLogger());
        $router
            ->on(new EventConfig('origin1', null), function(EventInterface $event) use (&$flag) {
                $flag = true;
            })
            ->on(new EventConfig('origin2', 'test'), function(EventInterface $event) use (&$flag) {
                $flag = true;
            });

        $router->send(new Event('origin2', 'non_test'));
        $this->assertFalse($flag);

        $router->send(new Event('origin3', 'test'));
        $this->assertFalse($flag);

        $router->send(new Event('origin2', 'test'));
        $this->assertTrue($flag);
    }

    /**
     * @return void
     */
    public function testSimpleLoop()
    {
        $log = [];
        $router = new EventRouter(3, new ArrayLogger());
        $router
            ->on(new EventConfig('origin1', null), function(EventInterface $event) use (&$log) {
                $log[] = $event->getName();
                return new Event('origin1', 'sub_event1');
            })
            ->on(new EventConfig('origin2', null), function(EventInterface $event) use (&$log) {
                $log[] = $event->getName();
                return [
                    new Event('origin2', 'sub_event2'),
                    new Event('origin2', 'sub_event3'),
                ];
            });

        try {
            $router->send(new Event('origin1', 'main_event'));
            $this->fail();
        } catch(EventRouterException $e) {
            $this->assertEquals(3, $e->getData()['max_depth_level_count']);
            $this->assertEquals(['main_event', 'sub_event1', 'sub_event1'], $log);
        }

        $log = [];
        try {
            $router->send(new Event('origin2', 'main_event'));
            $this->fail();
        } catch(EventRouterException $e) {
            $this->assertEquals(3, $e->getData()['max_depth_level_count']);
            $this->assertEquals(['main_event', 'sub_event2', 'sub_event2'], $log);
        }
    }

    /**
     * @return void
     */
    public function testComplicatedLoop()
    {
        $log = [];
        $router = new EventRouter(5, new ArrayLogger());
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
            $router->send(new Event('origin1', 'main_event'));
            $this->fail();
        } catch(EventRouterException $e) {
            $this->assertEquals(5, $e->getData()['max_depth_level_count']);
            $this->assertEquals(['main_event', 'sub_event1', 'sub_event2', 'sub_event1', 'sub_event2'], $log);
        }
    }

    /**
     * @return void
     * @throws EventRouterException
     */
    public function testRecipients()
    {
        $data = [];
        $logsContainer = SilentNestedAccessorFactory::fromArray($data);

        $router = new EventRouter(10, new ArrayLogger());
        $router->on(
            new EventConfig('origin1', null, [1, 2]),
            function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append($event->getOrigin(), $event->getName());
            }
        )->on(
            new EventConfig('origin2', null, [1, 3]),
            function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append($event->getOrigin(), $event->getName());
            }
        )->on(
            new EventConfig('origin2', 'test', [7, 9]),
            function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append($event->getOrigin(), $event->getName());
            }
        );

        $router->send(new Event('origin1', 'broadcast1'));
        $router->send(new Event('origin2', 'broadcast2'));

        $router->send(new Event('origin1', 'first1', [], [1]));
        $router->send(new Event('origin1', 'second1', [], [2]));
        $router->send(new Event('origin1', 'third1', [], [1, 2]));
        $router->send(new Event('origin1', 'forth1', [], [2, 3]));
        $router->send(new Event('origin1', 'fifth1', [], [3, 5]));

        $router->send(new Event('origin2', 'first2', [], [1]));
        $router->send(new Event('origin2', 'second2', [], [2]));
        $router->send(new Event('origin2', 'third2', [], [1, 2]));
        $router->send(new Event('origin2', 'forth2', [], [2, 3]));
        $router->send(new Event('origin2', 'fifth2', [], [3, 5]));

        $router->send(new Event('origin2', 'test', [], [7, 8]));
        $router->send(new Event('origin2', 'test', [], [8, 10]));

        $this->assertEquals([
            'first1',
            'second1',
            'third1',
            'forth1',
            'first2',
            'third2',
            'forth2',
            'fifth2',
            'test',
        ], array_map(function(EventInterface $event) {
            return $event->getName();
        }, $router->getLog()));

        $this->assertEquals(['first1', 'second1', 'third1', 'forth1'], $logsContainer->get('origin1'));
        $this->assertEquals(['first2', 'third2', 'forth2', 'fifth2', 'test'], $logsContainer->get('origin2'));
    }

    public function testExtraFilter()
    {
        $data = [];
        $logsContainer = SilentNestedAccessorFactory::fromArray($data);

        $router = new EventRouter(10, new ArrayLogger());
        $router->on(
            new EventConfig('origin1', null, null, function(EventInterface $event) {
                return isset($event->getData()['active']);
            }),
            function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append('active', $event->getName());
            }
        )->on(
            new EventConfig('origin1', 'special', null, function(EventInterface $event) {
                return isset($event->getData()['special']) && $event->getData()['special'];
            }),
            function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append('special', $event->getName());
            }
        )->on(
            new EventConfig('origin1'),
            function(EventInterface $event) use ($logsContainer) {
                $logsContainer->append('all', $event->getName());
            }
        );

        $router->send(new Event('origin1', 'active', ['active' => 1]));
        $router->send(new Event('origin1', 'non_active', ['non_active' => 1]));
        $router->send(new Event('origin1', 'special', ['special' => 1]));
        $router->send(new Event('origin1', 'special', ['non_special' => 1]));

        $this->assertEquals(['active', 'non_active', 'special', 'special'], $logsContainer->get('all'));
        $this->assertEquals(['active'], $logsContainer->get('active'));
        $this->assertEquals(['special'], $logsContainer->get('special'));
    }

    /**
     * @return void
     * @throws EventRouterException
     */
    public function testLogger()
    {
        $rule1 = new EventRouteRule(
            new EventConfig('origin1', null),
            function(EventInterface $event) {
                return [];
            }
        );
        $rule2 = new EventRouteRule(
            new EventConfig('origin2', null),
            function(EventInterface $event) {
                return [];
            }
        );

        $router = new EventRouter(10, new ArrayLogger());
        $router
            ->register($rule1)
            ->register($rule2);

        $router->send(new Event('origin1', 'first'));
        $router->send(new Event('origin1', 'second'));
        $router->send(new Event('origin2', 'third'));

        $this->assertEquals(['first', 'second', 'third'], array_map(function(EventInterface $event) {
            return $event->getName();
        }, $router->getLog()));
    }

    /**
     * @return void
     * @throws EventRouterException
     */
    public function testFakeLogger()
    {
        $router = new EventRouter(10, new FakeLogger());
        $router->on(
            new EventConfig('origin1', null),
            function() {
                return [];
            }
        )->on(
            new EventConfig('origin2', null),
            function() {
                return [];
            }
        );

        $router->send(new Event('origin1', 'broadcast1'));
        $router->send(new Event('origin2', 'broadcast2'));

        $this->assertEquals([], $router->getLog());
    }
}
