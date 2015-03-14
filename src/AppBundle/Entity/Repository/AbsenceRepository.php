<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * AbsenceRepository
 */
class AbsenceRepository extends EntityRepository
{
    public function findByTeamAndUser(Team $team, User $user)
    {
        return $this->findBy(['team' => $team->getId(), 'user' => $user->getId()]);
    }
}
