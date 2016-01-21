<?php

namespace Tests\AppBundle\Controller;

/**
 * Class TeamInvitationControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class TeamInvitationControllerTest extends AbstractControllerTest
{
    /**
     * @coversNothing
     */
    public function testIndexAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$usersFixtures, self::$teamFixtures, self::$invitationFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/team/1/invitation/');

        static::assertStatusCodeCustom($this->client);
        static::assertHeadline($crawler, 'invitation.template.index.title');
    }

    /**
     * @coversNothing
     */
    public function testInviteAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$usersFixtures, self::$teamFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/team/1/invite');

        static::assertStatusCodeCustom($this->client);
        static::assertHeadline($crawler, 'invitation.template.invite.title');

        // Test form

        $form = $crawler->selectButton('appbundle_invitation_form[submit]')->form();
        $form['appbundle_invitation_form[email]'] = 'test2@trackway.org';
        $crawler = $this->client->submit($form);

        static::assertStatusCodeCustom($this->client);
        static::assertNotification($crawler, 'invitation.flash.invited');
        static::assertHeadline($crawler, 'invitation.template.show.title');
    }

    /**
     * @coversNothing
     */
    public function testAcceptAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$usersFixtures, self::$teamFixtures, self::$invitationFixtures));
        $this->login('test2');

        // Test DB

        $invitation = $this->getContainer()->get('doctrine')->getRepository('AppBundle:Invitation')->findOneBy(['email' => 'test2@trackway.org']);

        self::assertNotEmpty($invitation);
        self::assertNotEmpty($invitation->getConfirmationToken());

        // Test view

        $crawler = $this->client->request('GET', '/team/invitation/' . $invitation->getConfirmationToken() . '/accept');

        static::assertStatusCodeCustom($this->client);
        static::assertNotification($crawler, 'invitation.flash.accepted');
        static::assertHeadline($crawler, 'calendar.template.index.title');
    }

    /**
     * @coversNothing
     */
    public function testRejectAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$usersFixtures, self::$teamFixtures, self::$invitationFixtures));
        $this->login('test2');

        // Test DB

        $invitation = $this->getContainer()->get('doctrine')->getRepository('AppBundle:Invitation')->findOneBy(['email' => 'test2@trackway.org']);

        self::assertNotEmpty($invitation);
        self::assertNotEmpty($invitation->getConfirmationToken());

        // Test view

        $crawler = $this->client->request('GET', '/team/invitation/' . $invitation->getConfirmationToken() . '/reject');

        static::assertStatusCodeCustom($this->client);
        static::assertNotification($crawler, 'invitation.flash.rejected');
        static::assertHeadline($crawler, 'calendar.template.index.title');
    }

    /**
     * @coversNothing
     */
    public function testShowAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$usersFixtures, self::$teamFixtures, self::$invitationFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/team/1/invitation/1');

        static::assertStatusCodeCustom($this->client);
        static::assertHeadline($crawler, 'invitation.template.show.title');
    }

    /**
     * @coversNothing
     */
    public function testDeleteAction()
    {
        // Prepare environment

        $this->loadFixtures(array_merge(self::$defaultFixtures, self::$usersFixtures, self::$teamFixtures, self::$invitationFixtures));
        $this->login();

        // Test view

        $crawler = $this->client->request('GET', '/team/1/invitation/1/delete');

        static::assertStatusCodeCustom($this->client);
        static::assertNotification($crawler, 'invitation.flash.deleted');
        static::assertHeadline($crawler, 'invitation.template.index.title');
    }
}
