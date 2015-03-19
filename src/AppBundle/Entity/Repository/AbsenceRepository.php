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
     * @param User $user
     *
     * @return Absence[]
     */
    public function findByTeamAndUser(Team $team, User $user)
    {
        return $this->findBy(['team' => $team->getId(), 'user' => $user->getId()]);
    }
}
