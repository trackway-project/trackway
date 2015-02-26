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
        return $this->findBy(['user' => $user->getId()]);
    }
}
