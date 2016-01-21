<?php

namespace Tests\AppBundle\Controller;

/**
 * Class TimeEntryControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class TimeEntryControllerTest extends AbstractControllerTest
{

    /**
     * @coversNothing
     */
    public function testNewAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures, self::$projectFixtures, self::$taskFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/timeentry/new');

        static::assertStatusCodeCustom($this->client);

        // Test form

        $form = $crawler->selectButton('appbundle_time_entry_form[submit]')->form();
        $form['appbundle_time_entry_form[dateTimeRange][date]'] = '2016-01-31';
        $form['appbundle_time_entry_form[dateTimeRange][endsAt]'] = '13:37';
        $form['appbundle_time_entry_form[dateTimeRange][startsAt]'] = '08:15';
        $form['appbundle_time_entry_form[note]'] = 'test';
        $form['appbundle_time_entry_form[project]'] = 1;
        $form['appbundle_time_entry_form[task]'] = 1;
        $this->client->submit($form);

        static::assertStatusCodeCustom($this->client);
    }

    /**
     * @coversNothing
     */
    public function testEditAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures,
            self::$userFixtures,
            self::$teamFixtures,
            self::$projectFixtures,
            self::$taskFixtures,
            self::$timeEntryFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/timeentry/1/edit');

        static::assertStatusCodeCustom($this->client);

        // Test form

        $form = $crawler->selectButton('appbundle_time_entry_form[submit]')->form();
        $this->client->submit($form);

        static::assertStatusCodeCustom($this->client);
    }

    /**
     * @coversNothing
     */
    public function testDeleteAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures,
            self::$userFixtures,
            self::$teamFixtures,
            self::$projectFixtures,
            self::$taskFixtures,
            self::$timeEntryFixtures));
        $this->login();

        // Test view

        $this->client->request('GET', '/timeentry/1/delete');

        static::assertStatusCodeCustom($this->client);
    }
}
