<?php

namespace AppBundle\Tests\Controller;

/**
 * Class ProjectControllerTest
 *
 * @package AppBundle\Tests\Controller
 */
class ProjectControllerTest extends AbstractControllerTest
{
    /**
     * @coversNothing
     */
    public function testIndexAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(
            self::$defaultFixtures,
            self::$userFixtures,
            self::$teamFixtures,
            self::$projectFixtures
        ));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/project/');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Projects")')->count());
    }

    /**
     * @coversNothing
     */
    public function testNewAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(
            self::$defaultFixtures,
            self::$userFixtures,
            self::$teamFixtures
        ));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/project/new');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Project new")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_project_form[submit]')->form();
        $form['appbundle_project_form[name]'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Project")')->count());
    }

    /**
     * @coversNothing
     */
    public function testShowAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(
            self::$defaultFixtures,
            self::$userFixtures,
            self::$teamFixtures,
            self::$projectFixtures
        ));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/project/1');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Project")')->count());
    }

    /**
     * @coversNothing
     */
    public function testEditAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(
            self::$defaultFixtures,
            self::$userFixtures,
            self::$teamFixtures,
            self::$projectFixtures
        ));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/project/1/edit');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Project edit")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_project_form[submit]')->form();
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Project")')->count());
    }

    /**
     * @coversNothing
     */
    public function testDeleteAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(
            self::$defaultFixtures,
            self::$userFixtures,
            self::$teamFixtures,
            self::$projectFixtures
        ));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/project/1/delete');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Projects")')->count());
    }
}
