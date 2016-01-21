<?php

namespace Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Locale;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadTestLocale
 *
 * @package Tests\AppBundle\DataFixtures\ORM
 */
class LoadTestLocale implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $locale = new Locale();
        $locale->setName('test');
        $manager->persist($locale);

        $manager->flush();
    }
}