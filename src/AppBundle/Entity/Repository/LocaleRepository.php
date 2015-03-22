<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Locale;
use Doctrine\ORM\EntityRepository;

/**
 * Class LocaleRepository
 *
 * @package AppBundle\Entity\Repository
 */
class LocaleRepository extends EntityRepository
{
    /**
     * @param $name
     *
     * @return null|Locale
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }
}
