<?php

namespace AppBundle\Test\Controller;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
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

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    public function setUp()
    {
        self::bootKernel();

        $this->client = static::createClient();
        $this->client->followRedirects(true);
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
    }

    protected function deleteUser()
    {
        // Make sure the user is not already existing
        $user = $this->em->getRepository('AppBundle:User')->findByEmail('test@trackway.org');
        if ($user) {
            $this->em->remove($user);
            $this->em->flush();
        }
    }

    protected function createUser()
    {
        // Create a new user
        $user = $this->em->getRepository('AppBundle:User')->findByEmail('test@trackway.org');
        if (!$user) {
            $user = new User();
            $user->setId(1);
            $user->setUsername('test');
            $user->setEmail('test@trackway.org');
            $user->setSalt('f0afecd49087e0971b807b3d5bb4d9f8');
            $user->setPassword('$2y$12$5cvVIqGw.reNtu7EWzMKq.cSVO5R26L446nT0PSW8SOcodwfFRGoS');
            $user->setRoles(['ROLE_USER']);
            $user->setLocale('en');
            $user->setEnabled(true);
            $user->setLastLogin(new \DateTime());

            $this->em->persist($user);
            $this->em->flush();
        }
    }

    protected function deleteTeam()
    {
        // Make sure the team is not already existing
        $team = $this->em->getRepository('AppBundle:Team')->findByName('test');
        if ($team) {
            $this->em->remove($team);
            $this->em->flush();
        }
    }

    protected function createTeam($active = true)
    {
        // Create a new user
        $team = $this->em->getRepository('AppBundle:Team')->findByEmail('test@trackway.org');
        if (!$team) {
            $team = new Team();
            $team->setId(1);
            $team->setName('test');

            $this->em->persist($team);

            if ($active) {
                $user = $this->em->getRepository('AppBundle:User')->findByEmail('test@trackway.org');
                if ($user) {
                    $user->setActiveTeam($team);

                    $this->em->persist($user);
                }
            }

            $this->em->flush();
        }
    }

    protected function login()
    {
        // Login with the new user
        $session = $this->client->getContainer()->get('session');

        $firewall = 'main';
        $token = new UsernamePasswordToken('test', null, $firewall, array('ROLE_USER'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
