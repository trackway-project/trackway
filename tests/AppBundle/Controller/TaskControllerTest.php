<?php

namespace Tests\AppBundle\Controller;

/**
 * Class TaskControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class TaskControllerTest extends AbstractControllerTest
{
    /**
     * @coversNothing
     */
    public function testIndexAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures, self::$taskFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/task/');

        static::assertStatusCodeCustom($this->client);
        static::assertHeadline($crawler, 'task.template.index.title');
    }

    /**
     * @coversNothing
     */
    public function testNewAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/task/new');

        static::assertStatusCodeCustom($this->client);
        static::assertHeadline($crawler, 'task.template.new.title');

        // Test form

        $form = $crawler->selectButton('appbundle_task_form[submit]')->form();
        $form['appbundle_task_form[name]'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertStatusCodeCustom($this->client);
        static::assertNotification($crawler, 'task.flash.created');
        static::assertHeadline($crawler, 'task.template.show.title');
    }

    /**
     * @coversNothing
     */
    public function testShowAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures, self::$taskFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/task/1');

        static::assertStatusCodeCustom($this->client);
        static::assertHeadline($crawler, 'task.template.show.title');
    }

    /**
     * @coversNothing
     */
    public function testEditAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures, self::$taskFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/task/1/edit');

        static::assertStatusCodeCustom($this->client);
        static::assertHeadline($crawler, 'task.template.edit.title');

        // Test form

        $form = $crawler->selectButton('appbundle_task_form[submit]')->form();
        $crawler = $this->client->submit($form);

        static::assertStatusCodeCustom($this->client);
        static::assertNotification($crawler, 'task.flash.updated');
        static::assertHeadline($crawler, 'task.template.show.title');
    }

    /**
     * @coversNothing
     */
    public function testDeleteAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures, self::$taskFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/task/1/delete');

        static::assertStatusCodeCustom($this->client);
        static::assertNotification($crawler, 'task.flash.deleted');
        static::assertHeadline($crawler, 'task.template.index.title');
    }
}
