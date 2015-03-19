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
        $illness->setName('illness');
        $manager->persist($illness);

        $vacation = new AbsenceReason();
        $vacation->setName('vacation');
        $manager->persist($vacation);

        $holiday = new AbsenceReason();
        $holiday->setName('holiday');
        $manager->persist($holiday);

        $manager->flush();
    }
}