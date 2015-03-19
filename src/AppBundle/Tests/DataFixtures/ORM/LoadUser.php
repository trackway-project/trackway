<?php

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadUser
 *
 * @package AppBundle\Tests\DataFixtures\ORM
 */
class LoadUser implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('test');
        $user->setEmail('test@trackway.org');
        $user->setSalt('f0afecd49087e0971b807b3d5bb4d9f8');
        $user->setPassword('$2y$12$5cvVIqGw.reNtu7EWzMKq.cSVO5R26L446nT0PSW8SOcodwfFRGoS');
        $user->setRoles(['ROLE_USER']);
        $user->setLocale($manager->getRepository('AppBundle:Locale')->findOneByName('en'));
        $user->setEnabled(true);
        $user->setLastLogin(new \DateTime());
        $manager->persist($user);

        $manager->flush();
    }
}