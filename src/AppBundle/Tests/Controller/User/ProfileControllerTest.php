<?php

namespace AppBundle\Tests\Controller\User;

use AppBundle\Tests\Controller\AbstractControllerTest;

/**
 * Class ProfileControllerTest
 *
 * @package AppBundle\Test\Controller\User
 */
class ProfileControllerTest extends AbstractControllerTest
{
    /**
     * @coversNothing
     */
    public function testShowAction()
    {
        // Prepare environment
        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/profile');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('h1:contains("profile.template.show.title")')->count());
    }

    /**
     * @coversNothing
     */
    public function testEditAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/profile/edit');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('h1:contains("profile.template.edit.title")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_profile_form[submit]')->form();
        $form['appbundle_profile_form[currentPassword]'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('div.alert:contains("profile.flash.updated")')->count());
        static::assertEquals(1, $crawler->filter('h1:contains("profile.template.show.title")')->count());
    }

    /**
     * @coversNothing
     */
    public function testChangePasswordAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/profile/change-password');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('h1:contains("profile.template.changePassword.title")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_change_password_form[submit]')->form();
        $form['appbundle_change_password_form[password][first]'] = 'test';
        $form['appbundle_change_password_form[password][second]'] = 'test';
        $form['appbundle_change_password_form[currentPassword]'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('div.alert:contains("profile.flash.passwordChanged")')->count());
        static::assertEquals(1, $crawler->filter('h1:contains("profile.template.show.title")')->count());
    }
}
