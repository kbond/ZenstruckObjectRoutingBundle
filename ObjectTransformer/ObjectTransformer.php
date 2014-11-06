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
     * @param object $object
     *
     * @return \Zenstruck\ObjectRoutingBundle\RouteContext
     */
    public function transform($object);

    /**
     * @param object $object
     *
     * @return bool
     */
    public function supports($object);
}
