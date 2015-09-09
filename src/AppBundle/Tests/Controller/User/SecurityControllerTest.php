<?php

namespace AppBundle\Tests\Controller\User;

use AppBundle\Tests\Controller\AbstractControllerTest;

/**
 * Class SecurityControllerTest
 *
 * @package AppBundle\Test\Controller\User
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

        static::assertStatusCode($this->client);
        static::assertMessage($crawler, 'security.template.login.message');

        // Test form submit

        $form = $crawler->selectButton('_submit')->form();
        $form['_username'] = 'test';
        $form['_password'] = 'test';
        $crawler = $this->client->submit($form);

        static::assertStatusCode($this->client);
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

        static::assertStatusCode($this->client);
        static::assertMessage($crawler, 'security.template.login.message');
    }
}
