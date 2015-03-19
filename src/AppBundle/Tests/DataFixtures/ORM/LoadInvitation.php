<?php

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Invitation;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadInvitation
 *
 * @package AppBundle\Tests\DataFixtures\ORM
 */
class LoadInvitation implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $invitation = new Invitation();
        $invitation->setConfirmationToken(md5(uniqid(mt_rand(), true)));
        $invitation->setEmail('test2@trackway.org');
        $invitation->setStatus($manager->getRepository('AppBundle:InvitationStatus')->findOneByName('open'));
        $manager->persist($invitation);

        $manager->flush();
    }
}