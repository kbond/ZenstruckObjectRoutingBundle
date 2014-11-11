<?php

namespace Zenstruck\ObjectRoutingBundle\ObjectTransformer;

/**
 * Converts object to a RouteContext.
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface ObjectTransformer
{
    /**
     * @param object      $object
     * @param null|string $routeName
     *
     * @return \Zenstruck\ObjectRoutingBundle\RouteContext
     */
    public function transform($object, $routeName = null);

    /**
     * @param object      $object
     * @param null|string $routeName
     *
     * @return bool
     */
    public function supports($object, $routeName = null);
}
