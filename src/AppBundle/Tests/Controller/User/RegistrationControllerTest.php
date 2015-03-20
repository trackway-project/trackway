<?php

namespace AppBundle\Tests\Controller\User;

use AppBundle\Tests\Controller\AbstractControllerTest;

/**
 * Class RegistrationControllerTest
 *
 * @package AppBundle\Test\Controller\User
 */
class RegistrationControllerTest extends AbstractControllerTest
{
    /**
     * @coversNothing
     */
    public function testRegisterAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures));

        // Test view

        $crawler = $this->client->request('GET', '/register');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('h1:contains("registration.template.register.title")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_registration_form[submit]')->form();
        $form['appbundle_registration_form[email]'] = 'test@trackway.org';
        $form['appbundle_registration_form[username]'] = 'test';
        $form['appbundle_registration_form[password][first]'] = 'test';
        $form['appbundle_registration_form[password][second]'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('div.alert:contains("registration.flash.registered")')->count());
        static::assertEquals(1, $crawler->filter('h1:contains("registration.template.checkMail.title")')->count());
    }


    /**
     * @coversNothing
     * @depends testRegisterAction
     */
    public function testConfirmAction()
    {
        // Test DB

        $user = $this->getContainer()->get('doctrine')->getRepository('AppBundle:User')->findOneByEmail('test@trackway.org');

        self::assertNotEmpty($user);
        self::assertNotEmpty($user->getConfirmationToken());

        // Test view

        $crawler = $this->client->request('GET', '/register/' . $user->getConfirmationToken());

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('div.alert:contains("registration.flash.confirmed")')->count());
        static::assertEquals(1, $crawler->filter('h1:contains("dashboard.template.index.title")')->count());
    }
}
