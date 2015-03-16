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

    protected function getDefaultFixtures()
    {
        return [
            'AppBundle\DataFixtures\ORM\LoadAbsenceReasons',
            'AppBundle\DataFixtures\ORM\LoadGroups',
            'AppBundle\DataFixtures\ORM\LoadInvitationStatuses',
            'AppBundle\DataFixtures\ORM\LoadLocales'
        ];
    }

    protected function getUserFixtures()
    {
        return [
            'AppBundle\Tests\DataFixtures\ORM\LoadUser'
        ];
    }

    protected function getTeamFixtures()
    {
        return [
            'AppBundle\Tests\DataFixtures\ORM\LoadUser'
        ];
    }

    protected function login($username = 'test', $password = 'test')
    {
        $this->client = static::makeClient(['username' => $username, 'password' => $password]);
        $this->client->followRedirects(true);
        $this->client->setMaxRedirects(10);
    }
}
