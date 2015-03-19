<?php

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\TimeEntry;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadTimeEntry
 *
 * @package AppBundle\Tests\DataFixtures\ORM
 */
class LoadTimeEntry implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $timeEntry = new TimeEntry();
        $timeEntry->setDate(new \DateTime());
        $timeEntry->setEndsAt(new \DateTime());
        $timeEntry->setNote('test');
        $timeEntry->setProject($manager->getRepository('AppBundle:Project')->findOneByName('test'));
        $timeEntry->setStartsAt(new \DateTime());
        $timeEntry->setTask($manager->getRepository('AppBundle:Task')->findOneByName('test'));
        $timeEntry->setTeam($manager->getRepository('AppBundle:Team')->findOneByName('test'));
        $timeEntry->setUser($manager->getRepository('AppBundle:User')->findOneByEmail('test@trackway.org'));
        $manager->persist($timeEntry);

        $manager->flush();
    }
}