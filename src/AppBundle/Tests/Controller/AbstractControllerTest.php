<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Client;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class AbstractControllerTest
 *
 * @package AppBundle\Test\Controller\User
 */
abstract class AbstractControllerTest extends WebTestCase
{
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

    protected function load()
    {
        $this->loadFixtures([
            'AppBundle\DataFixtures\ORM\LoadAbsenceReasons',
            'AppBundle\DataFixtures\ORM\LoadGroups',
            'AppBundle\DataFixtures\ORM\LoadInvitationStatuses',
            'AppBundle\DataFixtures\ORM\LoadLocales'
        ]);
    }

    protected function loadUser()
    {
        $this->loadFixtures([
            'AppBundle\DataFixtures\ORM\LoadAbsenceReasons',
            'AppBundle\DataFixtures\ORM\LoadGroups',
            'AppBundle\DataFixtures\ORM\LoadInvitationStatuses',
            'AppBundle\DataFixtures\ORM\LoadLocales',
            'AppBundle\Tests\DataFixtures\ORM\LoadUser'
        ]);
    }

    protected function loadUserWithActiveTeam()
    {
        $this->loadFixtures([
            'AppBundle\DataFixtures\ORM\LoadAbsenceReasons',
            'AppBundle\DataFixtures\ORM\LoadGroups',
            'AppBundle\DataFixtures\ORM\LoadInvitationStatuses',
            'AppBundle\DataFixtures\ORM\LoadLocales',
            'AppBundle\Tests\DataFixtures\ORM\LoadUserWithActiveTeam'
        ]);
    }

    protected function login($username = 'test', $password = 'test')
    {
        $this->client = static::makeClient(['username' => $username, 'password' => $password]);
        $this->client->followRedirects(true);
        $this->client->setMaxRedirects(10);
    }
}
