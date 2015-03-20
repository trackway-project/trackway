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

        static::assertStatusCode($this->client);
        static::assertHeadline($crawler, 'team.template.index.title');
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

        static::assertStatusCode($this->client);
        static::assertHeadline($crawler, 'team.template.new.title');

        // Test form

        $form = $crawler->selectButton('appbundle_team_form[submit]')->form();
        $form['appbundle_team_form[name]'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertStatusCode($this->client);
        static::assertFlashMessage($crawler, 'team.flash.created');
        static::assertHeadline($crawler, 'team.template.show.title');
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

        static::assertStatusCode($this->client);
        static::assertHeadline($crawler, 'team.template.show.title');
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

        static::assertStatusCode($this->client);
        static::assertHeadline($crawler, 'team.template.edit.title');

        // Test form

        $form = $crawler->selectButton('appbundle_team_form[submit]')->form();
        $crawler = $this->client->submit($form);

        static::assertStatusCode($this->client);
        static::assertFlashMessage($crawler, 'team.flash.updated');
        static::assertHeadline($crawler, 'team.template.show.title');
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

        static::assertStatusCode($this->client);
        static::assertFlashMessage($crawler, 'team.flash.deleted');
        static::assertHeadline($crawler, 'team.template.index.title');
    }
}
