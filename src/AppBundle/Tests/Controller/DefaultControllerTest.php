<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/app/example');

        static::assertEquals(200, $client->getResponse()->getStatusCode());
        static::assertTrue($crawler->filter('html:contains("Homepage")')->count() > 0);
    }
}
