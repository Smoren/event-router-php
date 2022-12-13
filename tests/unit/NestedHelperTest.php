<?php

namespace Smoren\EventRouter\Tests\Unit;

use Smoren\EventRouter\Exceptions\EventRouterException;
use Smoren\EventRouter\Helpers\NestedHelper;

class NestedHelperTest extends \Codeception\Test\Unit
{
    /**
     * @return void
     * @throws EventRouterException
     */
    public function testSimple()
    {
        $source = [
            'a' => ['b' => [['c' => 1], ['c' => 2]]],
        ];
        $this->assertEquals([1, 2], NestedHelper::get($source, ['a', 'b', 'c']));
        NestedHelper::set($source, ['a', 'd'], 22);
        $this->assertEquals([1, 2], NestedHelper::get($source, ['a', 'b', 'c']));
        $this->assertEquals(22, NestedHelper::get($source, ['a', 'd']));

        $source = [
            'test' => ['value' => 123],
        ];
        $this->assertEquals(123, NestedHelper::get($source, ['test', 'value']));
        $this->assertEquals(123, NestedHelper::get($source, 'test.value'));
        $this->assertEquals(null, NestedHelper::get($source, 'unknown.value', false));
    }
}
