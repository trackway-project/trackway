<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * MembershipRepository
 */
class MembershipRepository extends EntityRepository
{
    public function findAllByUser(User $user)
    {
        return $this->findBy(['user' => $user->getId()]);
    }
}
