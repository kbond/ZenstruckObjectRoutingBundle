<?php

namespace Zenstruck\ObjectRoutingBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Zenstruck\ObjectRoutingBundle\DependencyInjection\Compiler\ObjectTransformerCompilerPass;
use Zenstruck\ObjectRoutingBundle\DependencyInjection\Compiler\OverrideRoutingCompilerPass;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ZenstruckObjectRoutingBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ObjectTransformerCompilerPass());
        $container->addCompilerPass(new OverrideRoutingCompilerPass());

        parent::build($container);
    }
}
