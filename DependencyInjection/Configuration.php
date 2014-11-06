<?php

namespace Zenstruck\ObjectRoutingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('zenstruck_object_routing');

        $rootNode
            ->children()
                ->arrayNode('class_map')
                    ->useAttributeAsKey('class')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('route_name')->isRequired()->end()
                            ->arrayNode('route_parameters')->info('Route parameter as key, object method/public property as value')
                                ->useAttributeAsKey('route_parameter')
                                ->prototype('scalar')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
