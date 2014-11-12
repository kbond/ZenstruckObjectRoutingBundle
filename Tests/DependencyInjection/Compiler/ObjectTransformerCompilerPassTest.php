<?php

namespace Zenstruck\ObjectRoutingBundle\Tests\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\ObjectRoutingBundle\DependencyInjection\Compiler\ObjectTransformerCompilerPass;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ObjectTransformerCompilerPassTest extends AbstractCompilerPassTestCase
{
    public function testProcess()
    {
        $this->setDefinition('zenstruck_object_routing.router', new Definition());

        $transformer = new Definition();
        $transformer->addTag('zenstruck_object_routing.object_transformer');

        $this->setDefinition('my_transformer', $transformer);
        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'zenstruck_object_routing.router',
            'addTransformer',
            array(
                new Reference('my_transformer'),
            )
        );
    }

    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ObjectTransformerCompilerPass());
    }
}
