<?php

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Absence;
use AppBundle\Entity\TimeEntry;
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
        $absence = new Absence();
        $absence->setDate(new \DateTime());
        $absence->setEndsAt(new \DateTime());
        $absence->setNote('test');
        $absence->setReason($manager->getRepository('AppBundle:AbsenceReason')->findOneByName('Holiday'));
        $absence->setStartsAt(new \DateTime());
        $absence->setTeam($manager->getRepository('AppBundle:Team')->findOneByName('test'));
        $absence->setUser($manager->getRepository('AppBundle:User')->findOneByEmail('test@trackway.org'));
        $manager->persist($absence);

        $manager->flush();
    }
}