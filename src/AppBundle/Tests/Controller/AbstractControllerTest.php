<?php

namespace AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class AbstractControllerTest
 *
 * @package AppBundle\Test\Controller\User
 */
abstract class AbstractControllerTest extends WebTestCase
{
    protected static $defaultFixtures = ['AppBundle\DataFixtures\ORM\LoadAbsenceReasons', 'AppBundle\DataFixtures\ORM\LoadGroups', 'AppBundle\DataFixtures\ORM\LoadInvitationStatuses', 'AppBundle\DataFixtures\ORM\LoadLocales'];
    protected static $userFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadUser'];
    protected static $usersFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadUsers'];
    protected static $teamFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadTeam', 'AppBundle\Tests\DataFixtures\ORM\LoadMembershipOwner', 'AppBundle\Tests\DataFixtures\ORM\LoadActiveTeam'];
    protected static $projectFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadProject'];
    protected static $taskFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadTask'];
    protected static $timeEntryFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadTimeEntry'];
    protected static $absenceFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadAbsence'];
    protected static $membershipFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadMembership'];
    protected static $invitationFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadInvitation'];
    /**
     * @var Client
     */
    protected $client = null;

    protected function setUp()
    {
        $this->client = static::createClient();
        $this->client->followRedirects(true);
        $this->client->setMaxRedirects(10);
    }

    protected function login($username = 'test', $password = 'test')
    {
        $this->client = static::makeClient(['username' => $username, 'password' => $password]);
        $this->client->followRedirects(true);
        $this->client->setMaxRedirects(10);
    }
}
