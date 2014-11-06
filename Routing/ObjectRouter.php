<?php

namespace Zenstruck\ObjectRoutingBundle\Routing;

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;
use Zenstruck\ObjectRoutingBundle\ObjectTransformer\ObjectTransformer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ObjectRouter implements RouterInterface
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
        if (is_object($name) && ($routeContext = $this->transform($name))) {
            $name = $routeContext->getName();
            $parameters = array_merge($routeContext->getParameters(), $parameters);
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
     * @param object $object
     *
     * @return null|\Zenstruck\ObjectRoutingBundle\RouteContext
     */
    private function transform($object)
    {
        foreach ($this->transformers as $transformer) {
            if (!$transformer->supports($object)) {
                continue;
            }

            return $transformer->transform($object);
        }

        return null;
    }
}
