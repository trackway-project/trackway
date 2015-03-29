<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Team;
use Doctrine\ORM\EntityRepository;

/**
 * Class InvitationRepository
 *
 * @package AppBundle\Entity\Repository
 */
class InvitationRepository extends EntityRepository
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
        return $this->createQueryBuilder('i')->where('i.team = :team')->setParameter('team', $team->getId())->getQuery();
    }
}
