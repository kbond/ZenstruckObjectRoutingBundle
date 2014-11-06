<?php

namespace Zenstruck\ObjectRoutingBundle;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class RouteContext
{
    private $name;
    private $parameters;

    /**
     * @param string $name
     * @param array  $parameters
     */
    public function __construct($name, array $parameters)
    {
        $this->name = $name;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
