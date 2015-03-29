<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Absence;
use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Class AbsenceRepository
 *
 * @package AppBundle\Entity\Repository
 */
class AbsenceRepository extends EntityRepository
{
    /**
     * @param Team $team
     *
     * @return mixed
     */
    public function removeByTeam(Team $team)
    {
        return $this
            ->createQueryBuilder('a')
            ->delete()
            ->where('a.team = :team')
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
            ->createQueryBuilder('a')
            ->where('a.team = :team')
            ->andWhere('a.user = :user')
            ->setParameter('team', $team->getId())
            ->setParameter('user', $user->getId())
            ->getQuery();
    }
}
