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
    public function testTransform(array $classMap, $object, $routeName, $expectedName, array $expectedParameters)
    {
        $transformer = new ClassMapObjectTransformer($classMap);

        $routeContext = $transformer->transform($object, $routeName);

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
    public function testSupports(array $classMap, $object, $routeName, $expectedSupports)
    {
        $transformer = new ClassMapObjectTransformer($classMap);

        $this->assertSame($expectedSupports, $transformer->supports($object, $routeName));
    }

    public function transformDataProvider()
    {
        return array(
            array(
                array(
                    'Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureA' => array(
                        'default_route' => 'foo',
                        'default_parameters' => array('foo', 'baz')
                    )
                ),
                new FixtureA(),
                null,
                'foo',
                array('foo' => 'fooPropertyValue', 'baz' => 'bazMethodValue')
            ),
            array(
                array(
                    'Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureB' => '',
                    'Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureA' => array(
                        'default_route' => 'foo',
                        'default_parameters' => array('foo' => 'foo', 'baz' => 'baz')
                    )
                ),
                new FixtureA(),
                null,
                'foo',
                array('foo' => 'fooPropertyValue', 'baz' => 'bazMethodValue')
            ),
            array(
                array(
                    'Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureA' => array(
                        'default_route' => 'foo',
                        'default_parameters' => array('foo', 'baz')
                    )
                ),
                new FixtureA(),
                'foo',
                'foo',
                array('foo' => 'fooPropertyValue', 'baz' => 'bazMethodValue')
            ),
            array(
                array(
                    'Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureA' => array(
                        'default_route' => 'foo',
                        'routes' => array('foo' => array('foo', 'baz'))
                    )
                ),
                new FixtureA(),
                null,
                'foo',
                array('foo' => 'fooPropertyValue', 'baz' => 'bazMethodValue')
            ),
            array(
                array(
                    'Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureA' => array(
                        'default_route' => null,
                        'routes' => array('foo' => array('foo', 'baz'))
                    )
                ),
                new FixtureA(),
                'foo',
                'foo',
                array('foo' => 'fooPropertyValue', 'baz' => 'bazMethodValue')
            ),
            array(
                array(
                    'Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureA' => array(
                        'default_route' => null,
                        'default_parameters' => array('foo', 'baz'),
                        'routes' => array('foo' => array())
                    )
                ),
                new FixtureA(),
                'foo',
                'foo',
                array('foo' => 'fooPropertyValue', 'baz' => 'bazMethodValue')
            ),
        );
    }

    public function supportsDataProvider()
    {
        return array(
            array(
                array(),
                new \stdClass(),
                'foo',
                false
            ),
            array(
                array('stdClass' => array('default_route' => 'foo')),
                new \stdClass(),
                null,
                true
            ),
            array(
                array('stdClass' => array('default_route' => 'foo')),
                new \stdClass(),
                'foo',
                true
            ),
            array(
                array('stdClass' => array('default_route' => 'bar')),
                new \stdClass(),
                'foo',
                false
            ),
            array(
                array('stdClass' => array('default_route' => 'bar', 'routes' => array('foo' => '...'))),
                new \stdClass(),
                'foo',
                true
            ),
            array(
                array(),
                new FixtureA(),
                null,
                false
            ),
            array(
                array('Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureA' => array('default_route' => 'foo')),
                new FixtureA(),
                'foo',
                true
            ),
            array(
                array('Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureA' => array('default_route' => 'foo')),
                new FixtureB(),
                null,
                true
            ),
            array(
                array('Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureA' => '...'),
                new \stdClass(),
                null,
                false
            ),
            array(
                array('Zenstruck\ObjectRoutingBundle\Tests\ObjectTransformer\FixtureB' => '...'),
                new FixtureA(),
                'foo',
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
