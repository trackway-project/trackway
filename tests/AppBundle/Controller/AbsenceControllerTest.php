<?php

namespace Tests\AppBundle\Controller;

/**
 * Class AbsenceControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class AbsenceControllerTest extends AbstractControllerTest
{

    /**
     * @coversNothing
     */
    public function testNewAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures, self::$teamFixtures, self::$projectFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/absence/new');

        static::assertStatusCodeCustom($this->client);

        // Test form

        $form = $crawler->selectButton('appbundle_absence_form[submit]')->form();
        $form['appbundle_absence_form[dateTimeRange][date]'] = '2016-01-31';
        $form['appbundle_absence_form[dateTimeRange][endsAt]'] = '13:37';
        $form['appbundle_absence_form[dateTimeRange][startsAt]'] = '08:15';
        $form['appbundle_absence_form[note]'] = 'test';
        $form['appbundle_absence_form[reason]'] = 3;
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
            self::$absenceFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/absence/1/edit');

        static::assertStatusCodeCustom($this->client);

        // Test form

        $form = $crawler->selectButton('appbundle_absence_form[submit]')->form();
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
            self::$absenceFixtures));
        $this->login();

        // Test view

        $this->client->request('GET', '/absence/1/delete');

        static::assertStatusCodeCustom($this->client);
    }
}
