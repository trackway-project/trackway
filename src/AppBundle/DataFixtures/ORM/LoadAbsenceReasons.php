<?php


namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\AbsenceReason;
use AppBundle\Entity\Group;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadAbsenceReasons implements FixtureInterface{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $illness = new AbsenceReason();
        $illness->setName('Illness');
        $illness->setId(1);

        // force id's
        $metadata = $manager->getClassMetadata(get_class($illness));
        $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

        $manager->persist($illness);

        $vacation = new AbsenceReason();
        $vacation->setName('Vacation');
        $vacation->setId(2);
        $manager->persist($vacation);

        $holiday = new AbsenceReason();
        $holiday->setName('Holiday');
        $holiday->setId(3);
        $manager->persist($holiday);

        $manager->flush();
    }
}