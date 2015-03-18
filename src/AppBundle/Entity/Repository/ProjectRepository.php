<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Project;
use AppBundle\Entity\Team;
use Doctrine\ORM\EntityRepository;

/**
 * Class ProjectRepository
 *
 * @package AppBundle\Entity\Repository
 */
class ProjectRepository extends EntityRepository
{
    /**
     * @param Team $team
     *
     * @return Project[]
     */
    public function findByTeam(Team $team)
    {
        return $this->findBy(['team' => $team->getId()]);
    }

    /**
     * @param $name
     *
     * @return null|Project
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }
}
