<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Team;
use Doctrine\ORM\EntityRepository;

/**
 * Class InvitationRepository
 *
 * @package AppBundle\Entity\Repository
 */
class InvitationRepository extends EntityRepository
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
}
