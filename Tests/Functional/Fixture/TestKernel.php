<?php

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Zenstruck\ObjectRoutingBundle\ZenstruckObjectRoutingBundle;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class TestKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return array(
            new FrameworkBundle(),
            new ZenstruckObjectRoutingBundle(),
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config.yml');
    }

    public function getCacheDir()
    {
        return sys_get_temp_dir().'/ZenstruckObjectRoutingBundle/'.Kernel::VERSION.'/cache/'.$this->environment;
    }

    public function getLogDir()
    {
        return sys_get_temp_dir().'/ZenstruckObjectRoutingBundle/'.Kernel::VERSION.'/logs';
    }
}
