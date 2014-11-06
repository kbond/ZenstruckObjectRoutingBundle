<?php

namespace Zenstruck\ObjectRoutingBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Zenstruck\ObjectRoutingBundle\DependencyInjection\ZenstruckObjectRoutingExtension;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ZenstruckObjectRoutingExtensionTest extends AbstractExtensionTestCase
{
    public function testDefault()
    {
        $this->load();
        $this->compile();

        $this->assertContainerBuilderHasService('zenstruck_object_routing.router');
        $this->assertContainerBuilderNotHasService('zenstruck_object_routing.object_transformer.class_map');
    }

    public function testClassMapConfig()
    {
        $this->load(array(
                'class_map' => array(
                    'stdClass' => array(
                        'route_name' => 'foo',
                        'route_parameters' => array()
                    )
                )
            ));

        $this->compile();

        $this->assertContainerBuilderHasService('zenstruck_object_routing.router');
        $this->assertContainerBuilderHasService('zenstruck_object_routing.object_transformer.class_map');
    }

    protected function getContainerExtensions()
    {
        return array(new ZenstruckObjectRoutingExtension());
    }
}
