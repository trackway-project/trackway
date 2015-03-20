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

        static::assertStatusCode($this->client);
        static::assertHeadline($crawler, 'absence.template.index.title');
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

        static::assertStatusCode($this->client);
        static::assertHeadline($crawler, 'absence.template.new.title');

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

        static::assertStatusCode($this->client);
        static::assertFlashMessage($crawler, 'absence.flash.created');
        static::assertHeadline($crawler, 'absence.template.show.title');
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

        static::assertStatusCode($this->client);
        static::assertHeadline($crawler, 'absence.template.show.title');
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

        static::assertStatusCode($this->client);
        static::assertHeadline($crawler, 'absence.template.edit.title');

        // Test form

        $form = $crawler->selectButton('appbundle_absence_form[submit]')->form();
        $crawler = $this->client->submit($form);

        static::assertStatusCode($this->client);
        static::assertFlashMessage($crawler, 'absence.flash.updated');
        static::assertHeadline($crawler, 'absence.template.show.title');
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

        static::assertStatusCode($this->client);
        static::assertFlashMessage($crawler, 'absence.flash.deleted');
        static::assertHeadline($crawler, 'absence.template.index.title');
    }
}