<?php

namespace Zenstruck\ObjectRoutingBundle\Tests\Functional\Fixture;

use Zenstruck\ObjectRoutingBundle\ObjectTransformer\ObjectTransformer;
use Zenstruck\ObjectRoutingBundle\RouteContext;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class StdClassObjectTransformer implements ObjectTransformer
{
    public function transform($object, $routename = null)
    {
        return new RouteContext('std_class_show', array('foo' => 'bar'));
    }

    public function supports($object, $routename = null)
    {
        return $object instanceof \stdClass;
    }
}
