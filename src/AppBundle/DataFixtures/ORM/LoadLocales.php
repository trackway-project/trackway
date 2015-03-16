<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\AbsenceReason;
use AppBundle\Entity\Locale;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;

class LoadLocales implements FixtureInterface{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $en = new Locale();
        $en->setName('en');
        $manager->persist($en);

        $de = new Locale();
        $de->setName('de');
        $manager->persist($de);

        $manager->flush();
    }
}