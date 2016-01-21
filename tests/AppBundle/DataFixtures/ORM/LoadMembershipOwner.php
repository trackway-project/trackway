<?php

namespace Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Membership;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadMembershipOwner
 *
 * @package Tests\AppBundle\DataFixtures\ORM
 */
class LoadMembershipOwner implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $membership = new Membership();
        $membership->setGroup($manager->getRepository('AppBundle:Group')->findOneByName('owner'));
        $membership->setTeam($manager->getRepository('AppBundle:Team')->findOneByName('test'));
        $membership->setUser($manager->getRepository('AppBundle:User')->findOneByEmail('test@trackway.org'));
        $manager->persist($membership);

        $manager->flush();
    }
}