<?php

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\DateTimeRange;
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
        $dateTimeRange = new DateTimeRange();
        $dateTimeRange->setDate(new \DateTime());
        $dateTimeRange->setEndsAt(new \DateTime());
        $dateTimeRange->setStartsAt(new \DateTime());

        $timeEntry = new TimeEntry();
        $timeEntry->setDateTimeRange($dateTimeRange);
        $timeEntry->setNote('test');
        $timeEntry->setProject($manager->getRepository('AppBundle:Project')->findOneByName('test'));
        $timeEntry->setTask($manager->getRepository('AppBundle:Task')->findOneByName('test'));
        $timeEntry->setTeam($manager->getRepository('AppBundle:Team')->findOneByName('test'));
        $timeEntry->setUser($manager->getRepository('AppBundle:User')->findOneByEmail('test@trackway.org'));
        $manager->persist($timeEntry);

        $manager->flush();
    }
}
