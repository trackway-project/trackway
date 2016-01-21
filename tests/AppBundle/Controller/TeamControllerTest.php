<?php

namespace Tests\AppBundle\Controller;

/**
 * Class TeamControllerTest
 *
 * @package Tests\AppBundle\Controller
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

        static::assertStatusCodeCustom($this->client);
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

        static::assertStatusCodeCustom($this->client);
        static::assertHeadline($crawler, 'team.template.new.title');

        // Test form

        $form = $crawler->selectButton('appbundle_team_form[submit]')->form();
        $form['appbundle_team_form[name]'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertStatusCodeCustom($this->client);
        static::assertNotification($crawler, 'team.flash.created');
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

        static::assertStatusCodeCustom($this->client);
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

        static::assertStatusCodeCustom($this->client);
        static::assertHeadline($crawler, 'team.template.edit.title');

        // Test form

        $form = $crawler->selectButton('appbundle_team_form[submit]')->form();
        $crawler = $this->client->submit($form);

        static::assertStatusCodeCustom($this->client);
        static::assertNotification($crawler, 'team.flash.updated');
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

        static::assertStatusCodeCustom($this->client);
        static::assertNotification($crawler, 'team.flash.deleted');
        static::assertHeadline($crawler, 'team.template.index.title');
    }
}
