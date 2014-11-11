<?php

namespace Zenstruck\ObjectRoutingBundle\Tests\Functional\Fixture;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Zenstruck\ObjectRoutingBundle\Tests\Functional\Fixture\Entity\BlogPost;
use Zenstruck\ObjectRoutingBundle\Tests\Functional\Fixture\Entity\Page;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class TestController
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function testAction(Request $request, $type, $route = null, $absolute = null)
    {
        switch ($type) {
            case 'page':
                $name = new Page('foo', 'bar');
                break;
            case 'blog-post':
                $name = new BlogPost('foo', 'bar');
                break;
            case 'std-class':
                $name = new \stdClass();
                break;
            default:
                return $this->createResponse('page_show', array('path' => 'baz'));
        }

        if ($route && $absolute) {
            return $this->createResponse($route, $name, $request->query->all(), true);
        }

        if ($route) {
            return $this->createResponse($route, $name, $request->query->all());
        }

        return $this->createResponse($name, $request->query->all());
    }

    private function createResponse()
    {
        return new Response(call_user_func_array(array($this->router, 'generate'), func_get_args()));
    }
}
