<?php

namespace Zenstruck\ObjectRoutingBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Christophe Coevoet <stof@notk.org>
 * @author Francis Besset <francis.besset@gmail.com>
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @see https://github.com/BeSimple/BeSimpleI18nRoutingBundle
 */
class OverrideRoutingCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('zenstruck_object_routing.router')) {
            return;
        }

        if ($container->hasAlias('router')) {
            // router is an alias.
            // Register a private alias for this service to inject it as the parent
            $container->setAlias('zenstruck_object_routing.router.parent', new Alias((string) $container->getAlias('router'), false));
        } else {
            // router is a definition.
            // Register it again as a private service to inject it as the parent
            $definition = $container->getDefinition('router');
            $definition->setPublic(false);
            $container->setDefinition('zenstruck_object_routing.router.parent', $definition);
        }

        $container->setAlias('router', 'zenstruck_object_routing.router');
    }
}
