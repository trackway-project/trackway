<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\InvitationStatus;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadInvitationStatuses implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $open = new InvitationStatus();
        $open->setName('open');
        $manager->persist($open);

        $accepted = new InvitationStatus();
        $accepted->setName('accepted');
        $manager->persist($accepted);

        $rejected = new InvitationStatus();
        $rejected->setName('rejected');
        $manager->persist($rejected);

        $manager->flush();
    }
}