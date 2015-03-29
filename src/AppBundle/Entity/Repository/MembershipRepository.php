<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Class MembershipRepository
 *
 * @package AppBundle\Entity\Repository
 */
class MembershipRepository extends EntityRepository
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
        return $this->createQueryBuilder('m')->where('m.team = :team')->setParameter('team', $team->getId())->getQuery();
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function findByUser(User $user)
    {
        return $this->findBy(['user' => $user->getId()]);
    }
}
