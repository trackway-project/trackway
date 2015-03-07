<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * MembershipRepository
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
        return $this->findBy(['team' => $team->getId()]);
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
