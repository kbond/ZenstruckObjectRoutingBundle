<?php

namespace Zenstruck\ObjectRoutingBundle\Tests\Routing;

use \Mockery as m;
use Zenstruck\ObjectRoutingBundle\RouteContext;
use Zenstruck\ObjectRoutingBundle\Routing\ObjectRouter;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ObjectRouterTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateObject()
    {
        $fallbackRouter = m::mock('Symfony\Component\Routing\RouterInterface');
        $fallbackRouter
            ->shouldReceive('generate')
            ->once()
            ->with('foo', array('bar' => 'baz', 'foo' => 'bar'), false)
            ->andReturn('generate')
        ;

        $transformer1 = m::mock('Zenstruck\ObjectRoutingBundle\ObjectTransformer\ObjectTransformer');
        $transformer1->shouldReceive('supports')->once()->with(m::type('\stdClass'))->andReturn(false);

        $transformer2 = m::mock('Zenstruck\ObjectRoutingBundle\ObjectTransformer\ObjectTransformer');
        $transformer2->shouldReceive('supports')->once()->with(m::type('\stdClass'))->andReturn(true);
        $transformer2
            ->shouldReceive('transform')
            ->once()
            ->with(m::type('\stdClass'))
            ->andReturn(new RouteContext('foo', array('bar' => 'baz')))
        ;

        $router = new ObjectRouter($fallbackRouter, array($transformer1));
        $router->addTransformer($transformer2);

        $this->assertSame('generate', $router->generate(new \stdClass(), array('foo' => 'bar')));
    }

    public function testGenerateObjectNoTransformers()
    {
        $fallbackRouter = m::mock('Symfony\Component\Routing\RouterInterface');
        $fallbackRouter
            ->shouldReceive('generate')
            ->once()
            ->with(m::type('\stdClass'), array(), false)
            ->andReturn('generate')
        ;

        $router = new ObjectRouter($fallbackRouter);

        $this->assertSame('generate', $router->generate(new \stdClass()));
    }

    public function testFallback()
    {
        $fallbackRouter = m::mock('Symfony\Component\Routing\RouterInterface', 'Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface');
        $fallbackRouter->shouldReceive('match')->once()->with('foo')->andReturn(array('match'));
        $fallbackRouter->shouldReceive('generate')->once()->with('foo', array('bar'), true)->andReturn('generate');
        $fallbackRouter->shouldReceive('getRouteCollection')->once()->andReturn(m::mock('Symfony\Component\Routing\RouteCollection'));
        $fallbackRouter->shouldReceive('getContext')->once()->andReturn(m::mock('Symfony\Component\Routing\RequestContext'));
        $fallbackRouter->shouldReceive('setContext')->once()->with(m::type('Symfony\Component\Routing\RequestContext'));
        $fallbackRouter->shouldReceive('warmUp')->once()->with(m::type('string'));

        $router = new ObjectRouter($fallbackRouter);

        $this->assertSame(array('match'), $router->match('foo'));
        $this->assertSame('generate', $router->generate('foo', array('bar'), true));
        $this->assertInstanceOf('Symfony\Component\Routing\RouteCollection', $router->getRouteCollection());
        $this->assertInstanceOf('Symfony\Component\Routing\RequestContext', $router->getContext());

        $router->setContext(m::mock('Symfony\Component\Routing\RequestContext'));
        $router->warmUp('foo');
    }

    public function tearDown()
    {
        m::close();
    }
}
