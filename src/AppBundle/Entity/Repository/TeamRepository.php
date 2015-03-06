<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * TeamRepository
 */
class TeamRepository extends EntityRepository
{
    public function findAllByUser(User $user)
    {
        return $this->createQueryBuilder('t')->join('t.memberships', 'm')->where('m.user = ' . $user->getId())->getQuery()->getResult();
    }
}
