<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * TeamRepository
 */
class TeamRepository extends EntityRepository
{
    /**
     * @param Team $team
     */
    public function remove(Team $team)
    {
        $this->getEntityManager()->getRepository('AppBundle:TimeEntry')->removeByTeam($team);
        $this->getEntityManager()->remove($team);
        $this->getEntityManager()->flush();
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('t')->join('t.memberships', 'm')->where('m.user = ' . $user->getId())->getQuery()->getResult();
    }
}
