<?php

namespace AppBundle\Test\Controller\User;
use AppBundle\Test\Controller\AbstractControllerTest;

/**
 * Class SecurityControllerTest
 *
 * @package AppBundle\Test\Controller\User
 */
class SecurityControllerTest extends AbstractControllerTest
{
    public function testLoginAction()
    {
        // Prepare environment

        $this->createUser();

        // Test form view

        $crawler = $this->client->request('GET', '/login');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('h2:contains("security.login.heading")')->count());

        // Test form submit

        $form = $crawler->selectButton('_submit')->form();
        $form['_username'] = 'test';
        $form['_password'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('h1:contains("Dashboard")')->count());
    }

    /**
     * @depends testLoginAction
     */
    public function testLogoutAction()
    {
        $crawler = $this->client->request('GET', '/logout');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('h2:contains("security.login.heading")')->count());
    }
}
