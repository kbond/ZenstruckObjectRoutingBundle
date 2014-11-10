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
        );
    }
}
