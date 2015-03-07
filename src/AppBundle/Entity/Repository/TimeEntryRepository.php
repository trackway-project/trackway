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
        $this->getEntityManager()->createQuery('UPDATE AppBundle\Entity\User u SET u.activeTeam = null WHERE u.activeTeam = ?1')->setParameter(1, $team->getId())->execute();
        $this->getEntityManager()->createQuery('DELETE FROM AppBundle\Entity\TimeEntry t WHERE t.team = ?1')->setParameter(1, $team->getId())->execute();
        $this->getEntityManager()->flush();
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
