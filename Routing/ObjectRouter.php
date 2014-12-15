<?php

namespace Zenstruck\ObjectRoutingBundle\Routing;

use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;
use Zenstruck\ObjectRoutingBundle\ObjectTransformer\ObjectTransformer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ObjectRouter implements RouterInterface, WarmableInterface
{
    private $router;
    private $transformers;

    /**
     * @param RouterInterface     $router
     * @param ObjectTransformer[] $transformers
     */
    public function __construct(RouterInterface $router, array $transformers = array())
    {
        $this->router = $router;
        $this->transformers = $transformers;
    }

    /**
     * @param ObjectTransformer $transformer
     */
    public function addTransformer(ObjectTransformer $transformer)
    {
        $this->transformers[] = $transformer;
    }

    /**
     * {@inheritdoc}
     */
    public function setContext(RequestContext $context)
    {
        $this->router->setContext($context);
    }

    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        return $this->router->getContext();
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection()
    {
        return $this->router->getRouteCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        $routeName = is_object($name) ? null : $name;
        $object = $this->getObject($name, $parameters);

        // ensure parameters is array
        if (is_object($parameters)) {
            $parameters = array();
        }

        // allow 3rd arg to be extra parameters array
        if (is_array($referenceType)) {
            $parameters = $referenceType;
            $referenceType = self::ABSOLUTE_PATH;
        }

        // check for reference type as 4th arg
        if (3 < count($args = func_get_args())) {
            $referenceType = $args[3];
        }

        // check if first argument is an object
        if ($object && ($routeContext = $this->transform($object, $routeName))) {
            $name = $routeContext->getName();
            $parameters = array_merge($routeContext->getParameters(), $parameters);
            $uri = $this->router->generate($name, $parameters, $referenceType);

            if ($fragment = $routeContext->getFragment()) {
                $uri .= '#'.$fragment;
            }

            return $uri;
        }

        return $this->router->generate($name, $parameters, $referenceType);
    }

    /**
     * {@inheritdoc}
     */
    public function match($pathinfo)
    {
        return $this->router->match($pathinfo);
    }

    /**
     * {@inheritdoc}
     */
    public function warmUp($cacheDir)
    {
        if ($this->router instanceof WarmableInterface) {
            $this->router->warmUp($cacheDir);
        }
    }

    /**
     * @param object      $object
     * @param null|string $routeName
     *
     * @return null|\Zenstruck\ObjectRoutingBundle\RouteContext
     */
    private function transform($object, $routeName = null)
    {
        foreach ($this->transformers as $transformer) {
            if (!$transformer->supports($object, $routeName)) {
                continue;
            }

            return $transformer->transform($object, $routeName);
        }

        return null;
    }

    /**
     * @param object|string $name
     * @param array|object  $parameters
     *
     * @return null|object
     */
    private function getObject($name, $parameters)
    {
        if (is_object($name)) {
            return $name;
        }

        if (is_object($parameters)) {
            return $parameters;
        }

        return null;
    }
}
