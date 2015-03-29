<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * TeamRepository
 */
class TeamRepository extends EntityRepository
{
    /**
     * @param User $user
     *
     * @return Team[]
     */
    public function findByUser(User $user)
    {
        return $this->findByUserQuery($user)->getResult();
    }

    /**
     * Used for KnpPaginator.
     *
     * @param User $user
     *
     * @return \Doctrine\ORM\Query
     */
    public function findByUserQuery(User $user)
    {
        return $this->createQueryBuilder('t')->join('t.memberships', 'm')->where('m.user = ' . $user->getId())->getQuery();
    }

    /**
     * @param $name
     *
     * @return null|Team
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }

    /**
     * @param Team $team
     */
    public function remove(Team $team)
    {
        $this->getEntityManager()->getRepository('AppBundle:TimeEntry')->removeByTeam($team);
        $this->getEntityManager()->remove($team);
        $this->getEntityManager()->flush();
    }
}
