<?php

namespace Zenstruck\ObjectRoutingBundle\Tests\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Zenstruck\ObjectRoutingBundle\DependencyInjection\Compiler\OverrideRoutingCompilerPass;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class OverrideRoutingCompilerPassTest extends AbstractCompilerPassTestCase
{
    public function testProcessWithRouterDefinition()
    {
        $this->setDefinition('zenstruck_object_routing.router', new Definition());
        $this->setDefinition('router', new Definition());

        $this->compile();

        $this->assertContainerBuilderHasAlias('router', 'zenstruck_object_routing.router');
    }

    public function testProcessWithRouterAlias()
    {
        $this->setDefinition('zenstruck_object_routing.router', new Definition());
        $this->setDefinition('router.default', new Definition());
        $this->container->setAlias('router', 'router.default');

        $this->compile();

        $this->assertContainerBuilderHasAlias('router', 'zenstruck_object_routing.router');
    }

    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new OverrideRoutingCompilerPass());
    }
}
