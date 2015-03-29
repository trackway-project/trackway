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

        static::assertStatusCode($this->client);
        static::assertMessage($crawler, 'registration.template.register.message');

        // Test form

        $form = $crawler->selectButton('appbundle_registration_form[submit]')->form();
        $form['appbundle_registration_form[email]'] = 'test@trackway.org';
        $form['appbundle_registration_form[username]'] = 'test';
        $form['appbundle_registration_form[password][first]'] = 'test';
        $form['appbundle_registration_form[password][second]'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertStatusCode($this->client);
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

        static::assertStatusCode($this->client);
        static::assertFlashMessage($crawler, 'registration.flash.confirmed');
        static::assertHeadline($crawler, 'dashboard.template.index.title');
    }
}
