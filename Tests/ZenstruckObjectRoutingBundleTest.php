<?php

namespace Zenstruck\ObjectRoutingBundle\Tests;

use \Mockery as m;
use Zenstruck\ObjectRoutingBundle\ZenstruckObjectRoutingBundle;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ZenstruckObjectRoutingBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testCompilerPassesAreRegistered()
    {
        $container = m::mock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $container
            ->shouldReceive('addCompilerPass')
            ->twice()
            ->with(m::type('Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface'))
        ;

        $bundle = new ZenstruckObjectRoutingBundle();
        $bundle->build($container);
    }

    public function tearDown()
    {
        m::close();
    }
}
