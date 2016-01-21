<?php

namespace Tests\AppBundle\Controller\User;

use Tests\AppBundle\Controller\AbstractControllerTest;

/**
 * Class SecurityControllerTest
 *
 * @package Tests\AppBundle\Controller\User
 */
class SecurityControllerTest extends AbstractControllerTest
{
    /**
     * @coversNothing
     */
    public function testLoginAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures));

        // Test form view

        $crawler = $this->client->request('GET', '/login');

        static::assertStatusCodeCustom($this->client);
        static::assertMessage($crawler, 'security.template.login.message');

        // Test form submit

        $form = $crawler->selectButton('_submit')->form();
        $form['_username'] = 'test';
        $form['_password'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertStatusCodeCustom($this->client);
        static::assertHeadline($crawler, 'calendar.template.index.title');
    }

    /**
     * TODO: Does not work in unit tests - find solution
     *
     * @coversNothing
     */
    public function ignoreLogoutAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures));
        $this->login();

        // Test

        $crawler = $this->client->request('GET', '/logout');

        static::assertStatusCodeCustom($this->client);
        static::assertMessage($crawler, 'security.template.login.message');
    }
}
