<?php

namespace Zenstruck\ObjectRoutingBundle\Tests;

use Zenstruck\ObjectRoutingBundle\RouteContext;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class RouteContextTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $routeContext = new RouteContext('foo', array('bar' => 'baz'));

        $this->assertSame('foo', $routeContext->getName());
        $this->assertSame(array('bar' => 'baz'), $routeContext->getParameters());
    }
}
