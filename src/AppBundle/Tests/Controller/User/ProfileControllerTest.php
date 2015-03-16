<?php

namespace AppBundle\Tests\Controller\User;

use AppBundle\Entity\User;
use AppBundle\Tests\Controller\AbstractControllerTest;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class ProfileControllerTest
 *
 * @package AppBundle\Test\Controller\User
 */
class ProfileControllerTest extends AbstractControllerTest
{
    public function testShowAction()
    {
        // Prepare environment

        $this->loadUser();
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/profile');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('h1:contains("Profile")')->count());
    }

    public function testEditAction()
    {
        // Prepare environment

        $this->loadUser();
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/profile/edit');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('h1:contains("Profile edit")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_profile_form[submit]')->form();
        $form['appbundle_profile_form[locale]'] = 2;
        $form['appbundle_profile_form[currentPassword]'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /team/');
        static::assertEquals(1, $crawler->filter('td:contains("locale.de")')->count());
    }
}
