<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * TimeEntryRepository
 */
class TimeEntryRepository extends EntityRepository
{
    /**
     * @param Team $team
     *
     * @return array
     */
    public function removeByTeam(Team $team)
    {
        $this->_em->createQuery('DELETE FROM TimeEntry t WHERE t.team_id = ?1')->setParameter(1, $team->getId())->execute();
        $this->_em->flush();
    }

    /**
     * @param Team $team
     * @param User $user
     *
     * @return array
     */
    public function findByTeamAndUser(Team $team, User $user)
    {
        return $this->findBy(['team' => $team->getId(), 'user' => $user->getId()]);
    }
}
