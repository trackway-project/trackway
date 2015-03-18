<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Task;
use AppBundle\Entity\Team;
use Doctrine\ORM\EntityRepository;

/**
 * Class TaskRepository
 *
 * @package AppBundle\Entity\Repository
 */
class TaskRepository extends EntityRepository
{
    /**
     * @param Team $team
     *
     * @return Task[]
     */
    public function findByTeam(Team $team)
    {
        return $this->findBy(['team' => $team->getId()]);
    }

    /**
     * @param $name
     *
     * @return null|Task
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }
}
