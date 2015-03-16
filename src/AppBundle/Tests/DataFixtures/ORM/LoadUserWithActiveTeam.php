<?php

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Group;
use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;

class LoadUserWithActiveTeam implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $team = new Team();
        $team->setName('test');
        $manager->persist($team);

        $user = new User();
        $user->setUsername('test');
        $user->setEmail('test@trackway.org');
        $user->setSalt('f0afecd49087e0971b807b3d5bb4d9f8');
        $user->setPassword('$2y$12$5cvVIqGw.reNtu7EWzMKq.cSVO5R26L446nT0PSW8SOcodwfFRGoS');
        $user->setRoles(['ROLE_USER']);
        $user->setLocale($manager->getRepository('AppBundle:Locale')->findOneByName('en'));
        $user->setEnabled(true);
        $user->setLastLogin(new \DateTime());
        $user->setActiveTeam($team);
        $manager->persist($user);

        $manager->flush();
    }
}