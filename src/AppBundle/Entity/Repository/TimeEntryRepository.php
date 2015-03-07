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
    public function findAllByTeamAndUser(Team $team, User $user)
    {
        return $this->findBy(['team' => $team->getId(), 'user' => $user->getId()]);
    }
}
