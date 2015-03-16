<?php

namespace AppBundle\Tests\Controller\User;

use AppBundle\Entity\User;
use AppBundle\Tests\Controller\AbstractControllerTest;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class ResettingControllerTest
 *
 * @package AppBundle\Test\Controller\User
 */
class ResettingControllerTest extends AbstractControllerTest
{
    public function testRequestAction()
    {
        // Prepare environment

        $this->loadUser();

        // Test view

        $crawler = $this->client->request('GET', '/resetting');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('h1:contains("Reset request")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_resetting_request_form[submit]')->form();
        $form['appbundle_resetting_request_form[email]'] = 'test@trackway.org';
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('p:contains("Please check your mails and click on the confirmation link.")')->count());
    }

    /**
     * @depends testRequestAction
     */
    public function testConfirmAction()
    {
        // Test DB

        $user = $this->getContainer()->get('doctrine')->getRepository('AppBundle:User')->findByEmail('test@trackway.org');

        self::assertNotEmpty($user);
        self::assertNotEmpty($user->getConfirmationToken());

        // Test view

        $crawler = $this->client->request('GET', '/resetting/' . $user->getConfirmationToken());

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('h1:contains("Reset confirm")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_resetting_confirm_form[submit]')->form();
        $form['appbundle_resetting_confirm_form[password][first]'] = 'foobar';
        $form['appbundle_resetting_confirm_form[password][second]'] = 'foobar';
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('h1:contains("Dashboard")')->count());
    }
}
