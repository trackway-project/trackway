<?php

namespace Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadUsers
 *
 * @package Tests\AppBundle\DataFixtures\ORM
 */
class LoadUsers implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('test');
        $user->setEmail('test@trackway.org');
        $user->setPassword('$2y$12$0DEwmswbClJRpMtcjvXKaOWO8NHletqQd.8vWuSEm5OJ48Gg9wada');
        $user->setRoles(['ROLE_USER']);
        $user->setLocale($manager->getRepository('AppBundle:Locale')->findOneByName('test'));
        $user->setEnabled(true);
        $user->setLastLogin(new \DateTime());
        $manager->persist($user);

        $user = new User();
        $user->setUsername('test2');
        $user->setEmail('test2@trackway.org');
        $user->setPassword('$2y$12$0DEwmswbClJRpMtcjvXKaOWO8NHletqQd.8vWuSEm5OJ48Gg9wada');
        $user->setRoles(['ROLE_USER']);
        $user->setLocale($manager->getRepository('AppBundle:Locale')->findOneByName('test'));
        $user->setEnabled(true);
        $user->setLastLogin(new \DateTime());
        $manager->persist($user);

        $manager->flush();
    }
}