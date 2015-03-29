<?php

namespace AppBundle\Tests\Controller\User;

use AppBundle\Entity\User;
use AppBundle\Tests\Controller\AbstractControllerTest;

/**
 * Class ResettingControllerTest
 *
 * @package AppBundle\Test\Controller\User
 */
class ResettingControllerTest extends AbstractControllerTest
{
    /**
     * @coversNothing
     */
    public function testRequestAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$userFixtures));

        // Test view

        $crawler = $this->client->request('GET', '/resetting');

        static::assertStatusCode($this->client);
        static::assertMessage($crawler, 'resetting.template.request.message');

        // Test form

        $form = $crawler->selectButton('appbundle_resetting_request_form[submit]')->form();
        $form['appbundle_resetting_request_form[email]'] = 'test@trackway.org';
        $crawler = $this->client->submit($form);

        static::assertStatusCode($this->client);
        static::assertFlashMessage($crawler, 'resetting.flash.resetted');
        static::assertMessage($crawler, 'resetting.template.checkMail.message');
    }

    /**
     * @coversNothing
     * @depends testRequestAction
     */
    public function testConfirmAction()
    {
        // Test DB

        $user = $this->getContainer()->get('doctrine')->getRepository('AppBundle:User')->findOneByEmail('test@trackway.org');

        self::assertNotEmpty($user);
        self::assertNotEmpty($user->getConfirmationToken());

        // Test view

        $crawler = $this->client->request('GET', '/resetting/' . $user->getConfirmationToken());

        static::assertStatusCode($this->client);
        static::assertMessage($crawler, 'resetting.template.confirm.message');

        // Test form

        $form = $crawler->selectButton('appbundle_resetting_confirm_form[submit]')->form();
        $form['appbundle_resetting_confirm_form[password][first]'] = 'foobar';
        $form['appbundle_resetting_confirm_form[password][second]'] = 'foobar';
        $crawler = $this->client->submit($form);

        static::assertStatusCode($this->client);
        static::assertFlashMessage($crawler, 'resetting.flash.confirmed');
        static::assertHeadline($crawler, 'dashboard.template.index.title');
    }
}
