<?php

namespace Tests\AppBundle\Controller\User;

use Tests\AppBundle\Controller\AbstractControllerTest;

/**
 * Class RegistrationControllerTest
 *
 * @package Tests\AppBundle\Controller\User
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

        static::assertStatusCodeCustom($this->client);
        static::assertMessage($crawler, 'registration.template.register.message');

        // Test form

        $form = $crawler->selectButton('appbundle_registration_form[submit]')->form();
        $form['appbundle_registration_form[email]'] = 'test@trackway.org';
        $form['appbundle_registration_form[username]'] = 'test';
        $form['appbundle_registration_form[password][first]'] = 'test';
        $form['appbundle_registration_form[password][second]'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertStatusCodeCustom($this->client);
        static::assertFlashMessage($crawler, 'registration.flash.registered');
        static::assertMessage($crawler, 'registration.template.checkMail.message');
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

        static::assertStatusCodeCustom($this->client);
        static::assertNotification($crawler, 'registration.flash.confirmed');
        static::assertHeadline($crawler, 'calendar.template.index.title');
    }
}
