<?php

namespace AppBundle\Tests\Controller;

/**
 * Class TaskControllerTest
 *
 * @package AppBundle\Tests\Controller
 */
class TaskControllerTest extends AbstractControllerTest
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
            self::$taskFixtures
        ));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/task/');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Tasks")')->count());
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

        $crawler = $this->client->request('GET', '/task/new');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Task new")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_task_form[submit]')->form();
        $form['appbundle_task_form[name]'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Task")')->count());
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
            self::$taskFixtures
        ));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/task/1');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Task")')->count());
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
            self::$taskFixtures
        ));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/task/1/edit');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Task edit")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_task_form[submit]')->form();
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Task")')->count());
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
            self::$taskFixtures
        ));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/task/1/delete');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("Tasks")')->count());
    }
}
