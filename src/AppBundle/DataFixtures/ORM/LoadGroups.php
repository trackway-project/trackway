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
        $owner = new Group('Owner');
        $owner->setId(1);
        $owner->setRoles(['ROLE_ADMIN']);

        // force id's
        $metadata = $manager->getClassMetadata(get_class($owner));
        $metadata->setIdGenerator(new AssignedGenerator());
        $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

        $manager->persist($owner);

        $admin = new Group('Admin');
        $admin->setId(2);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $user = new Group('User');
        $user->setId(3);
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $manager->flush();
    }
}