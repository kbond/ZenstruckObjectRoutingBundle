<?php

namespace Zenstruck\ObjectRoutingBundle;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class RouteContext
{
    private $name;
    private $parameters;
    private $fragment;

    /**
     * @param string      $name       The route name
     * @param array       $parameters Parameters for the route
     * @param string|null $fragment   Fragment ("#hash") to add to the generated uri
     */
    public function __construct($name, array $parameters, $fragment = null)
    {
        $this->name = $name;
        $this->parameters = $parameters;
        $this->fragment = $fragment;
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

    /**
     * @return string|null
     */
    public function getFragment()
    {
        return $this->fragment;
    }
}
