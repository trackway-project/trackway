<?php

namespace Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Task;
use AppBundle\Entity\Team;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadTask
 *
 * @package Tests\AppBundle\DataFixtures\ORM
 */
class LoadTask implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $task = new Task();
        $task->setName('test');
        $task->setTeam($manager->getRepository('AppBundle:Team')->findOneByName('test'));
        $manager->persist($task);

        $manager->flush();
    }
}