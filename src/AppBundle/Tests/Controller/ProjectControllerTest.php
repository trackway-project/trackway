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

        static::assertStatusCode($this->client);
        static::assertHeadline($crawler, 'project.template.index.title');
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

        static::assertStatusCode($this->client);
        static::assertHeadline($crawler, 'project.template.new.title');

        // Test form

        $form = $crawler->selectButton('appbundle_project_form[submit]')->form();
        $form['appbundle_project_form[name]'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertStatusCode($this->client);
        static::assertFlashMessage($crawler, 'project.flash.created');
        static::assertHeadline($crawler, 'project.template.show.title');
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

        static::assertStatusCode($this->client);
        static::assertHeadline($crawler, 'project.template.show.title');
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

        static::assertStatusCode($this->client);
        static::assertHeadline($crawler, 'project.template.edit.title');

        // Test form

        $form = $crawler->selectButton('appbundle_project_form[submit]')->form();
        $crawler = $this->client->submit($form);

        static::assertStatusCode($this->client);
        static::assertFlashMessage($crawler, 'project.flash.updated');
        static::assertHeadline($crawler, 'project.template.show.title');
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

        static::assertStatusCode($this->client);
        static::assertFlashMessage($crawler, 'project.flash.deleted');
        static::assertHeadline($crawler, 'project.template.index.title');
    }
}
