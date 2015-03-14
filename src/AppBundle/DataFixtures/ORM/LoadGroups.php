<?php


namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Group;
use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadGroups implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $owner = new Group('Owner');
        $owner->setId(1);
        $owner->addRole('ROLE_ADMIN');

        // force id's
        $metadata = $manager->getClassMetadata(get_class($owner));
        $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

        $manager->persist($owner);

        $admin = new Group('Admin');
        $admin->setId(2);
        $admin->addRole('ROLE_ADMIN');
        $manager->persist($admin);

        $user = new Group('User');
        $user->setId(3);
        $user->addRole('ROLE_USER');
        $manager->persist($user);




        $manager->flush();
    }
}