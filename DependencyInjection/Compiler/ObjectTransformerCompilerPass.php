<?php

namespace Zenstruck\ObjectRoutingBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ObjectTransformerCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('zenstruck_object_routing.router')) {
            return;
        }

        $definition = $container->getDefinition('zenstruck_object_routing.router');
        $taggedServices = $container->findTaggedServiceIds('zenstruck_object_routing.object_transformer');

        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall('addTransformer', array(new Reference($id)));
        }
    }
}
