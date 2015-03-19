<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\AbsenceReason;
use Doctrine\ORM\EntityRepository;

/**
 * Class AbsenceReasonRepository
 *
 * @package AppBundle\Entity\Repository
 */
class AbsenceReasonRepository extends EntityRepository
{
    /**
     * @param $name
     *
     * @return null|AbsenceReason
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }
}
