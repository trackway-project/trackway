<?php

namespace Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Project;
use AppBundle\Entity\Team;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadProject
 *
 * @package Tests\AppBundle\DataFixtures\ORM
 */
class LoadProject implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $project = new Project();
        $project->setName('test');
        $project->setTeam($manager->getRepository('AppBundle:Team')->findOneByName('test'));
        $manager->persist($project);

        $manager->flush();
    }
}