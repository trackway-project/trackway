<?php

namespace Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Team;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadTeam
 *
 * @package Tests\AppBundle\DataFixtures\ORM
 */
class LoadTeam implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $team = new Team();
        $team->setName('test');
        $manager->persist($team);

        $manager->flush();
    }
}