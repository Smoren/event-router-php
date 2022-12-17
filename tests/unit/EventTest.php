<?php

namespace Smoren\EventRouter\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\EventRouter\Events\Event;

class EventTest extends Unit
{
    /**
     * @return void
     */
    public function testEvent()
    {
        $event = new Event('test', 'name', ['a' => 1], [1, 2, 3]);
        $this->assertEquals('test', $event->getOrigin());
        $this->assertEquals('name', $event->getName());
        $this->assertEquals(['a' => 1], $event->getData());
        $this->assertEquals([1, 2, 3], $event->getRecipients());
        $this->assertEquals([
            'origin' => 'test',
            'name' => 'name',
            'recipients' => [1, 2, 3],
            'data' => ['a' => 1],
        ], $event->toArray());

        $link = $event->getLink();
        $this->assertEquals(['a' => 1], $link->getData());
        $this->assertEquals('', $link->getId());
        $this->assertEquals('array', $link->getType());
    }
}
