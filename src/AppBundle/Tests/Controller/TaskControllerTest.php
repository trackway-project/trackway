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

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures, self::$taskFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/task/');

        static::assertStatusCode($this->client);
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

        static::assertStatusCode($this->client);
        static::assertHeadline($crawler, 'task.template.new.title');

        // Test form

        $form = $crawler->selectButton('appbundle_task_form[submit]')->form();
        $form['appbundle_task_form[name]'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertStatusCode($this->client);
        static::assertFlashMessage($crawler, 'task.flash.created');
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

        static::assertStatusCode($this->client);
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

        static::assertStatusCode($this->client);
        static::assertHeadline($crawler, 'task.template.edit.title');

        // Test form

        $form = $crawler->selectButton('appbundle_task_form[submit]')->form();
        $crawler = $this->client->submit($form);

        static::assertStatusCode($this->client);
        static::assertFlashMessage($crawler, 'task.flash.updated');
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

        static::assertStatusCode($this->client);
        static::assertFlashMessage($crawler, 'task.flash.deleted');
        static::assertHeadline($crawler, 'task.template.index.title');
    }
}
