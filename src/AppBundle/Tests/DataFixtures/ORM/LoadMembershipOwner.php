<?php

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Membership;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;

class LoadMembershipOwner implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $membership = new Membership();
        $membership->setGroup($manager->getRepository('AppBundle:Group')->findOneByName('Owner'));
        $membership->setTeam($manager->getRepository('AppBundle:Team')->findOneByName('test'));
        $membership->setUser($manager->getRepository('AppBundle:User')->findOneByEmail('test@trackway.org'));
        $manager->persist($membership);

        $manager->flush();
    }
}