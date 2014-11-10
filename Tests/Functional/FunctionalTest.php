<?php

namespace Zenstruck\ObjectRoutingBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class FunctionalTest extends WebTestCase
{
    /**
     * @dataProvider uriDataProvider
     */
    public function testPageAction($uri, $expectedContent)
    {
        $client = static::createClient();
        $client->request('GET', $uri);
        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expectedContent, $response->getContent());
    }

    public function uriDataProvider()
    {
        return array(
            array('/test/page', '/page/bar'),
            array('/test/page?foo=baz', '/page/bar?foo=baz'),
            array('/test/blog-post', '/blog/foo/bar'),
            array('/test/std-class', '/std-class/bar'),
            array('/test/default', '/page/baz'),
            array('/test/blog-post/blog_post_edit', '/blog/foo/edit'),
            array('/test/blog-post/blog_post_delete', '/blog/foo/delete'),
            array('/test/blog-post/blog_post_edit?foo=bar', '/blog/foo/edit?foo=bar'),
            array('/test/blog-post/blog_post_edit/absolute', 'http://localhost/blog/foo/edit'),
        );
    }
}
