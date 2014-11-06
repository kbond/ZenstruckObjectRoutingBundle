<?php

namespace Zenstruck\ObjectRoutingBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ZenstruckObjectRoutingExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('router.xml');

        if (count($classMap = $mergedConfig['class_map'])) {
            $container->setParameter('zenstruck_object_routing.class_map', $classMap);
            $loader->load('class_map.xml');
        }
    }
}
