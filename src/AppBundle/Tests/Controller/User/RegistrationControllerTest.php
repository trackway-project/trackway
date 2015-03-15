<?php

namespace AppBundle\Test\Controller\User;

use AppBundle\Test\Controller\AbstractControllerTest;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class RegistrationControllerTest
 *
 * @package AppBundle\Test\Controller\User
 */
class RegistrationControllerTest extends AbstractControllerTest
{
    public function testRegisterAction()
    {
        // Prepare environment

        $this->deleteUser();

        // Test view

        $crawler = $this->client->request('GET', '/register');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('h1:contains("Registration")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_registration_form[submit]')->form();
        $form['appbundle_registration_form[email]'] = 'test@trackway.org';
        $form['appbundle_registration_form[username]'] = 'test';
        $form['appbundle_registration_form[password][first]'] = 'test';
        $form['appbundle_registration_form[password][second]'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('p:contains("Please check your mails and click on the confirmation link.")')->count());
    }

    /**
     * @depends testRegisterAction
     */
    public function testConfirmAction()
    {
        // Test DB

        $user = $this->em->getRepository('AppBundle:User')->findByEmail('test@trackway.org');

        self::assertNotEmpty($user);

        // Test view

        $crawler = $this->client->request('GET', '/register/' . $user->getConfirmationToken());

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('h1:contains("Dashboard")')->count());
    }
}
