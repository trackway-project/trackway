<?php

namespace AppBundle\Tests\Controller;

/**
 * Class AbsenceControllerTest
 *
 * @package AppBundle\Tests\Controller
 */
class AbsenceControllerTest extends AbstractControllerTest
{
    /**
     * @coversNothing
     */
    public function testIndexAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures, self::$projectFixtures, self::$taskFixtures, self::$absenceFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/absence/');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("absence.template.index.title")')->count());
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

        $crawler = $this->client->request('GET', '/absence/new');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("absence.template.new.title")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_absence_form[submit]')->form();
        $form['appbundle_absence_form[date][year]'] = 2016;
        $form['appbundle_absence_form[date][month]'] = 11;
        $form['appbundle_absence_form[date][day]'] = 3;
        $form['appbundle_absence_form[endsAt][hour]'] = 13;
        $form['appbundle_absence_form[endsAt][minute]'] = 37;
        $form['appbundle_absence_form[startsAt][hour]'] = 11;
        $form['appbundle_absence_form[startsAt][minute]'] = 00;
        $form['appbundle_absence_form[note]'] = 'test';
        $form['appbundle_absence_form[reason]'] = 3;
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('div.alert:contains("absence.flash.created")')->count());
        static::assertEquals(1, $crawler->filter('h1:contains("absence.template.show.title")')->count());
    }

    /**
     * @coversNothing
     */
    public function testShowAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures, self::$projectFixtures, self::$taskFixtures, self::$absenceFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/absence/1');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("absence.template.show.title")')->count());
    }

    /**
     * @coversNothing
     */
    public function testEditAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures, self::$projectFixtures, self::$taskFixtures, self::$absenceFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/absence/1/edit');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("absence.template.edit.title")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_absence_form[submit]')->form();
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('div.alert:contains("absence.flash.updated")')->count());
        static::assertEquals(1, $crawler->filter('h1:contains("absence.template.show.title")')->count());
    }

    /**
     * @coversNothing
     */
    public function testDeleteAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures, self::$projectFixtures, self::$taskFixtures, self::$absenceFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/absence/1/delete');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('div.alert:contains("absence.flash.deleted")')->count());
        static::assertEquals(1, $crawler->filter('h1:contains("absence.template.index.title")')->count());
    }
}