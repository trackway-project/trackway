<?php

namespace AppBundle\Tests\Controller;

/**
 * Class TeamInvitationControllerTest
 *
 * @package AppBundle\Tests\Controller
 */
class TeamInvitationControllerTest extends AbstractControllerTest
{
    /**
     * @coversNothing
     */
    public function testIndexAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(
            self::$defaultFixtures,
            self::$usersFixtures,
            self::$teamFixtures,
            self::$invitationFixtures
        ));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/team/1/invitation/');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("invitation.template.index.title")')->count());
    }

    /**
     * @coversNothing
     */
    public function testInviteAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(
            self::$defaultFixtures,
            self::$usersFixtures,
            self::$teamFixtures
        ));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/team/1/invite');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("invitation.template.invite.title")')->count());

        // Test form

        $form = $crawler->selectButton('appbundle_invitation_form[submit]')->form();
        $form['appbundle_invitation_form[email]'] = 'test2@trackway.org';
        $crawler = $this->client->submit($form);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('div.alert:contains("invitation.flash.invited")')->count());
        static::assertEquals(1, $crawler->filter('h1:contains("invitation.template.show.title")')->count());
    }

    /**
     * @coversNothing
     */
    public function testAcceptAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(
            self::$defaultFixtures,
            self::$usersFixtures,
            self::$teamFixtures,
            self::$invitationFixtures
        ));
        $this->login('test2');

        // Test DB

        $invitation = $this->getContainer()->get('doctrine')->getRepository('AppBundle:Invitation')->findOneBy(['email' => 'test2@trackway.org']);

        self::assertNotEmpty($invitation);
        self::assertNotEmpty($invitation->getConfirmationToken());

        // Test view

        $crawler = $this->client->request('GET', '/team/invitation/' . $invitation->getConfirmationToken() . '/accept');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('div.alert:contains("invitation.flash.accepted")')->count());
        static::assertEquals(1, $crawler->filter('h1:contains("dashboard.template.index.title")')->count());
    }

    /**
     * @coversNothing
     */
    public function testRejectAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(
            self::$defaultFixtures,
            self::$usersFixtures,
            self::$teamFixtures,
            self::$invitationFixtures
        ));
        $this->login('test2');

        // Test DB

        $invitation = $this->getContainer()->get('doctrine')->getRepository('AppBundle:Invitation')->findOneBy(['email' => 'test2@trackway.org']);

        self::assertNotEmpty($invitation);
        self::assertNotEmpty($invitation->getConfirmationToken());

        // Test view

        $crawler = $this->client->request('GET', '/team/invitation/' . $invitation->getConfirmationToken() . '/reject');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('div.alert:contains("invitation.flash.rejected")')->count());
        static::assertEquals(1, $crawler->filter('h1:contains("dashboard.template.index.title")')->count());
    }

    /**
     * @coversNothing
     */
    public function testShowAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(
            self::$defaultFixtures,
            self::$usersFixtures,
            self::$teamFixtures,
            self::$invitationFixtures
        ));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/team/1/invitation/1');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('h1:contains("invitation.template.show.title")')->count());
    }

    /**
     * @coversNothing
     */
    public function testDeleteAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(
            self::$defaultFixtures,
            self::$usersFixtures,
            self::$teamFixtures,
            self::$invitationFixtures
        ));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/team/1/invitation/1/delete');

        static::assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Unexpected HTTP status code');
        static::assertEquals(1, $crawler->filter('div.alert:contains("invitation.flash.deleted")')->count());
        static::assertEquals(1, $crawler->filter('h1:contains("invitation.template.index.title")')->count());
    }
}
