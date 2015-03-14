<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Team;
use Doctrine\ORM\EntityRepository;

/**
 * TaskRepository
 */
class TaskRepository extends EntityRepository
{
    public function findByTeam(Team $team)
    {
        return $this->findBy(['team' => $team->getId()]);
    }
}
