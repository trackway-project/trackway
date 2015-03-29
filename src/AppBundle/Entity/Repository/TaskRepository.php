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
     * @return array
     */
    public function findByTeam(Team $team)
    {
        return $this->findByTeamQuery($team)->getResult();
    }

    /**
     * @param Team $team
     *
     * @return \Doctrine\ORM\Query
     */
    public function findByTeamQuery(Team $team)
    {
        return $this->createQueryBuilder('t')->where('t.team = :team')->setParameter('team', $team->getId())->getQuery();
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
