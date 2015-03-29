<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * TimeEntryRepository
 */
class TimeEntryRepository extends EntityRepository
{
    /**
     * @param Team $team
     *
     * @return mixed
     */
    public function removeByTeam(Team $team)
    {
        return $this
            ->createQueryBuilder('t')
            ->delete()
            ->where('t.team = :team')
            ->setParameter('team', $team->getId())
            ->getQuery()
            ->execute();
    }

    /**
     * @param Team $team
     * @param User $user
     *
     * @return array
     */
    public function findByTeamAndUser(Team $team, User $user)
    {
        return $this->findByTeamAndUserQuery($team, $user)->getResult();
    }

    /**
     * Used for KnpPaginator.
     *
     * @param Team $team
     * @param User $user
     *
     * @return \Doctrine\ORM\Query
     */
    public function findByTeamAndUserQuery(Team $team, User $user)
    {
        return $this
            ->createQueryBuilder('t')
            ->where('t.team = :team')
            ->andWhere('t.user = :user')
            ->setParameter('team', $team->getId())
            ->setParameter('user', $user->getId())
            ->getQuery();
    }
}
