<?php

namespace Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadActiveTeam
 *
 * @package Tests\AppBundle\DataFixtures\ORM
 */
class LoadActiveTeam implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $manager->getRepository('AppBundle:User')->findOneByEmail('test@trackway.org');
        $user->setActiveTeam($manager->getRepository('AppBundle:Team')->findOneByName('test'));
        $manager->persist($user);

        $manager->flush();
    }
}