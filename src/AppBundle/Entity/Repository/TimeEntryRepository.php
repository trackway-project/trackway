<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Team;
use Doctrine\ORM\EntityRepository;

/**
 * TimeEntryRepository
 */
class TimeEntryRepository extends EntityRepository
{
    public function findAllByTeam(Team $team)
    {
        return $this->findBy(array('team' => $team->getId()));
    }
}
