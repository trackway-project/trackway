<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Group;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;

class LoadGroups implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $owner = new Group();
        $owner->setName('Owner');
        $owner->setRoles(['ROLE_ADMIN']);
        $manager->persist($owner);

        $admin = new Group();
        $admin->setName('Admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $user = new Group();
        $user->setName('User');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $manager->flush();
    }
}