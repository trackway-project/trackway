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
        return $this->createQueryBuilder('p')->where('p.team = :team')->setParameter('team', $team->getId())->getQuery();
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
