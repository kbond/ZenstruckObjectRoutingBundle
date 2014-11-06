<?php

namespace Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer;

use Zenstruck\ObjectRoutingBundle\ObjectTransformer\ClassMapObjectTransformer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ClassMapObjectTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider transformDataProvider
     */
    public function testTransform(array $classMap, $object, $expectedName, array $expectedParameters)
    {
        $transformer = new ClassMapObjectTransformer($classMap);

        $routeContext = $transformer->transform($object);

        $this->assertInstanceOf('Zenstruck\ObjectRoutingBundle\RouteContext', $routeContext);
        $this->assertSame($expectedName, $routeContext->getName());
        $this->assertSame($expectedParameters, $routeContext->getParameters());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Could not transform class "stdClass"
     */
    public function testTransformInvalid()
    {
        $transformer = new ClassMapObjectTransformer(array());

        $transformer->transform(new \stdClass());
    }

    /**
     * @dataProvider supportsDataProvider
     */
    public function testSupports(array $classMap, $object, $expectedSupports)
    {
        $transformer = new ClassMapObjectTransformer($classMap);

        $this->assertSame($expectedSupports, $transformer->supports($object));
    }

    public function transformDataProvider()
    {
        return array(
            array(
                array(
                    'Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureA' => array(
                        'route_name' => 'foo',
                        'route_parameters' => array('foo' => 'foo', 'baz' => 'baz')
                    )
                ),
                new FixtureA(),
                'foo',
                array('foo' => 'fooPropertyValue', 'baz' => 'bazMethodValue')
            ),
            array(
                array(
                    'Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureB' => '',
                    'Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureA' => array(
                        'route_name' => 'foo',
                        'route_parameters' => array('foo' => 'foo', 'baz' => 'baz')
                    )
                ),
                new FixtureA(),
                'foo',
                array('foo' => 'fooPropertyValue', 'baz' => 'bazMethodValue')
            )
        );
    }

    public function supportsDataProvider()
    {
        return array(
            array(
                array(),
                new \stdClass(),
                false
            ),
            array(
                array('stdClass' => '...'),
                new \stdClass(),
                true
            ),
            array(
                array(),
                new FixtureA(),
                false
            ),
            array(
                array('Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureA' => '...'),
                new FixtureA(),
                true
            ),
            array(
                array('Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureA' => '...'),
                new FixtureB(),
                true
            ),
            array(
                array('Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureA' => '...'),
                new \stdClass(),
                false
            ),
            array(
                array('Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureB' => '...'),
                new FixtureA(),
                false
            ),
        );
    }
}

class FixtureA
{
    public $foo = 'fooPropertyValue';

    public function getBaz()
    {
        return 'bazMethodValue';
    }
}

class FixtureB extends FixtureA {}
