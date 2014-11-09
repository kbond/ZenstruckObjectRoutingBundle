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
    public function transform($object)
    {
        $objectClass = get_class($object);

        foreach ($this->classMap as $class => $config) {
            if (!$this->isClassSupported($objectClass, $class)) {
                continue;
            }

            $accessor = PropertyAccess::createPropertyAccessor();

            $parameters = array_map(
                function ($value) use ($object, $accessor) {
                    return $accessor->getValue($object, $value);
                },
                $config['route_parameters']
            );

            return new RouteContext($config['route_name'], $parameters);
        }

        throw new \InvalidArgumentException(sprintf('Could not transform class "%s"', $objectClass));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($object)
    {
        $objectClass = get_class($object);

        foreach ($this->classMap as $class => $config) {
            if ($this->isClassSupported($objectClass, $class)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $objectClass
     * @param string $class
     *
     * @return bool
     */
    private function isClassSupported($objectClass, $class)
    {
        return $objectClass === $class || is_subclass_of($objectClass, $class);
    }
}
