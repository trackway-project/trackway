<?php

namespace AppBundle\Tests\Controller;

/**
 * Class TeamControllerTest
 *
 * @package AppBundle\Tests\Controller
 */
class TeamControllerTest extends AbstractControllerTest
{
    /**
     * @coversNothing
     */
    public function testIndexAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/team/');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Teams")')->count());
    }

    /**
     * @coversNothing
     */
    public function testNewAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/team/new');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Team new")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_team_form[submit]')->form();
        $form['appbundle_team_form[name]'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Team")')->count());
    }

    /**
     * @coversNothing
     */
    public function testShowAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/team/1');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Team")')->count());
    }

    /**
     * @coversNothing
     */
    public function testEditAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/team/1/edit');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Team edit")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_team_form[submit]')->form();
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Team")')->count());
    }

    /**
     * @coversNothing
     */
    public function testDeleteAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/team/1/delete');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Teams")')->count());
    }
}
