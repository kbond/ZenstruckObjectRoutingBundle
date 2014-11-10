<?php

namespace Zenstruck\ObjectRoutingBundle\Tests\Functional\Fixture;

use Zenstruck\ObjectRoutingBundle\ObjectTransformer\ObjectTransformer;
use Zenstruck\ObjectRoutingBundle\RouteContext;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class StdClassObjectTransformer implements ObjectTransformer
{
    public function transform($object)
    {
        return new RouteContext('std_class_show', array('foo' => 'bar'));
    }

    public function supports($object)
    {
        return $object instanceof \stdClass;
    }
}
