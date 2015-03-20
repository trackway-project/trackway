<?php

namespace AppBundle\Tests\Controller;

/**
 * Class TimeEntryControllerTest
 *
 * @package AppBundle\Tests\Controller
 */
class TimeEntryControllerTest extends AbstractControllerTest
{
    /**
     * @coversNothing
     */
    public function testIndexAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures, self::$projectFixtures, self::$taskFixtures, self::$timeEntryFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/timeentry/');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("timeEntry.template.index.title")')->count());
    }

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

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("timeEntry.template.new.title")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_time_entry_form[submit]')->form();
        $form['appbundle_time_entry_form[date][year]'] = 2016;
        $form['appbundle_time_entry_form[date][month]'] = 11;
        $form['appbundle_time_entry_form[date][day]'] = 3;
        $form['appbundle_time_entry_form[endsAt][hour]'] = 13;
        $form['appbundle_time_entry_form[endsAt][minute]'] = 37;
        $form['appbundle_time_entry_form[startsAt][hour]'] = 11;
        $form['appbundle_time_entry_form[startsAt][minute]'] = 00;
        $form['appbundle_time_entry_form[note]'] = 'test';
        $form['appbundle_time_entry_form[project]'] = 1;
        $form['appbundle_time_entry_form[task]'] = 1;
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('div.alert:contains("timeEntry.flash.created")')->count());
        static::assertEquals(1, $crawler->filter('h1:contains("timeEntry.template.show.title")')->count());
    }

    /**
     * @coversNothing
     */
    public function testShowAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures, self::$projectFixtures, self::$taskFixtures, self::$timeEntryFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/timeentry/1');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("timeEntry.template.show.title")')->count());
    }

    /**
     * @coversNothing
     */
    public function testEditAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures, self::$projectFixtures, self::$taskFixtures, self::$timeEntryFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/timeentry/1/edit');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("timeEntry.template.edit.title")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_time_entry_form[submit]')->form();
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('div.alert:contains("timeEntry.flash.updated")')->count());
        static::assertEquals(1, $crawler->filter('h1:contains("timeEntry.template.show.title")')->count());
    }

    /**
     * @coversNothing
     */
    public function testDeleteAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures, self::$projectFixtures, self::$taskFixtures, self::$timeEntryFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/timeentry/1/delete');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('div.alert:contains("timeEntry.flash.deleted")')->count());
        static::assertEquals(1, $crawler->filter('h1:contains("timeEntry.template.index.title")')->count());
    }
}