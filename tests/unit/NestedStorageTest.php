<?php

namespace Smoren\EventRouter\Tests\Unit;

use Smoren\EventRouter\Components\NestedArrayStorage;
use Smoren\EventRouter\Exceptions\EventRouterException;

class NestedStorageTest extends \Codeception\Test\Unit
{
    /**
     * @return void
     * @throws EventRouterException
     */
    public function testSimple()
    {
        $ns = new NestedArrayStorage([
            'a' => ['b' => [['c' => 1], ['c' => 2]]],
        ]);
        $this->assertEquals([1, 2], $ns->get(['a', 'b', 'c']));
        $ns->set(['a', 'd'], 22);
        $this->assertEquals([1, 2], $ns->get(['a', 'b', 'c']));
        $this->assertEquals(22, $ns->get(['a', 'd']));
    }
}
