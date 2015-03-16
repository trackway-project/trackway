<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\AbsenceReason;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;

class LoadAbsenceReasons implements FixtureInterface{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $illness = new AbsenceReason();
        $illness->setName('Illness');
        $manager->persist($illness);

        $vacation = new AbsenceReason();
        $vacation->setName('Vacation');
        $manager->persist($vacation);

        $holiday = new AbsenceReason();
        $holiday->setName('Holiday');
        $manager->persist($holiday);

        $manager->flush();
    }
}