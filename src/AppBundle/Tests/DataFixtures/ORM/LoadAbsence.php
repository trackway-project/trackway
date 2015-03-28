<?php

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Absence;
use AppBundle\Entity\DateTimeRange;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadAbsence
 *
 * @package AppBundle\Tests\DataFixtures\ORM
 */
class LoadAbsence implements FixtureInterface
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

        $absence = new Absence();
        $absence->setDateTimeRange($dateTimeRange);
        $absence->setNote('test');
        $absence->setReason($manager->getRepository('AppBundle:AbsenceReason')->findOneByName('holiday'));
        $absence->setTeam($manager->getRepository('AppBundle:Team')->findOneByName('test'));
        $absence->setUser($manager->getRepository('AppBundle:User')->findOneByEmail('test@trackway.org'));
        $manager->persist($absence);

        $manager->flush();
    }
}
