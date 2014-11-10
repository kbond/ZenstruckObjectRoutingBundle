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
                            ->scalarNode('default_route')
                                ->defaultNull()
                                ->info('Optional - The route to use when an object is passed as the 1st parameter of Router::generate()')
                            ->end()
                            ->arrayNode('default_parameters')
                                ->info('Route parameter as key, object method/public property as value (can omit key if object method/property is the same)')
                                ->example(array('id', 'path'))
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('routes')
                                ->info('Route name as key, parameter array as value (can leave parameter array as null if same as default_parameters)')
                                ->example(array('blog_show' => '~', 'blog_edit' => array('id')))
                                ->useAttributeAsKey('route_name')
                                ->prototype('array')
                                    ->prototype('scalar')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
