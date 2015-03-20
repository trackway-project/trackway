<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Group;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadGroups implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $owner = new Group();
        $owner->setName('owner');
        $owner->setRoles(['ROLE_ADMIN']);
        $manager->persist($owner);

        $admin = new Group();
        $admin->setName('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $user = new Group();
        $user->setName('user');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $manager->flush();
    }
}