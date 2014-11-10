<?php

namespace Zenstruck\ObjectRoutingBundle\ObjectTransformer;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Zenstruck\ObjectRoutingBundle\RouteContext;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ClassMapObjectTransformer implements ObjectTransformer
{
    private $classMap;

    public function __construct(array $classMap)
    {
        $this->classMap = $classMap;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($object, $routeName = null)
    {
        $objectClass = get_class($object);

        foreach ($this->classMap as $class => $config) {
            if (!$this->isSupported($objectClass, $class, $routeName, $config)) {
                continue;
            }

            $routeName = null === $routeName ? $config['default_route'] : $routeName;
            $routeParameters = (isset($config['routes'][$routeName]) && count($config['routes'][$routeName])) ?
                $config['routes'][$routeName] : $config['default_parameters'];

            $accessor = PropertyAccess::createPropertyAccessor();
            $parameters = array();

            foreach ($routeParameters as $key => $value) {
                // when array isn't assoc, use value as both parameter and accessor
                $parameters[is_numeric($key) ? $value : $key] = $accessor->getValue($object, $value);
            }

            return new RouteContext($routeName, $parameters);
        }

        throw new \InvalidArgumentException(sprintf('Could not transform class "%s"', $objectClass));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($object, $routeName = null)
    {
        $objectClass = get_class($object);

        foreach ($this->classMap as $class => $config) {
            if ($this->isSupported($objectClass, $class, $routeName, $config)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string      $objectClass
     * @param string      $class
     * @param null|string $routeName
     * @param array       $config
     *
     * @return bool
     */
    private function isSupported($objectClass, $class, $routeName, $config)
    {
        if ($objectClass !== $class && !is_subclass_of($objectClass, $class)) {
            return false;
        }

        if (null === $routeName && null !== $config['default_route']) {
            return true;
        }

        if ($routeName === $config['default_route'] || isset($config['routes'][$routeName])) {
            return true;
        }

        return false;
    }
}
