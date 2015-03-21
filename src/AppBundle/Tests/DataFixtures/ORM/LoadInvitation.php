<?php

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Invitation;
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
        $invitation->setTeam($manager->getRepository('AppBundle:Team')->findOneByName('test'));
        $manager->persist($invitation);

        $manager->flush();
    }
}