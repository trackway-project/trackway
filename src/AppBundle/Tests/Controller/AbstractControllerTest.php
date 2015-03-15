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
        // Delete old user
        $user = $this->em->getRepository('AppBundle:User')->findByEmail('test@trackway.org');
        if ($user) {
            $this->em->remove($user);
            $this->em->flush();
        }
    }

    protected function createUser()
    {
        // Delete old user
        $this->deleteUser();

        // Create new user
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

    protected function deleteTeam()
    {
        // Login
        $this->login();

        // Make sure the team is not already existing
        $team = $this->em->getRepository('AppBundle:Team')->findByName('test');
        if ($team) {
            $this->em->remove($team);
            $this->em->flush();
        }
    }

    protected function createTeam($active = true)
    {
        // Delete old team
        $this->deleteTeam();

        // Create a new user
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

    protected function login()
    {
        // Create new user
        $this->createUser();

        // Login
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('_submit')->form();
        $form['_username'] = 'test';
        $form['_password'] = 'test';
        $crawler = $this->client->submit($form);
    }
}
