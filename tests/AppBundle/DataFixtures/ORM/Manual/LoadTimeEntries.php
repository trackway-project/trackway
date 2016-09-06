<?php

namespace Tests\AppBundle\DataFixtures\ORM\Manual;

use AppBundle\Entity\DateTimeRange;
use AppBundle\Entity\TimeEntry;
use Tests\AppBundle\LoremIpsumHelper;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadTimeEntries
 *
 * @package Tests\AppBundle\DataFixtures\ORM
 */
class LoadTimeEntries implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $startDate = new \DateTime('2014-01-01');
        $endDate = new \DateTime('2015-04-01');
        $dateInterval = \DateInterval::createFromDateString('1 day');
        $datePeriod = new \DatePeriod($startDate, $dateInterval, $endDate);

        /** @var \DateTime $date */
        foreach ($datePeriod as $date) {
            if ($this->isWeekend($date->getTimestamp())) {
                continue;
            }

            /** @var \DateTime $startDateTime */
            $startDateTime = clone $date;
            $startDateTime->setTime(9, 0);
            /** @var \DateTime $endDateTime */
            $endDateTime = clone $date;
            $endDateTime->setTime(17, 0);
            $dateTimeInterval = \DateInterval::createFromDateString('1 hour');
            $dateTimePeriod = new \DatePeriod($startDateTime, $dateTimeInterval, $endDateTime);

            /** @var \DateTime $rangeStartDateTime */
            foreach ($dateTimePeriod as $rangeStartDateTime) {
                /** @var \DateTime $rangeEndDateTime */
                $rangeEndDateTime = clone $rangeStartDateTime;
                $rangeEndDateTime->add($dateTimeInterval);

                $dateTimeRange = new DateTimeRange();
                $dateTimeRange->setDate($date);
                $dateTimeRange->setStartsAt($rangeStartDateTime);
                $dateTimeRange->setEndsAt($rangeEndDateTime);

                $timeEntry = new TimeEntry();
                $timeEntry->setDateTimeRange($dateTimeRange);
                $timeEntry->setNote(LoremIpsumHelper::loremIpsum(0, 0, rand(1, 8)));
                $timeEntry->setProject($manager->getRepository('AppBundle:Project')->find(1));
                $timeEntry->setTeam($manager->getRepository('AppBundle:Team')->find(1));
                $timeEntry->setUser($manager->getRepository('AppBundle:User')->find(1));

                $manager->persist($timeEntry);
            }
        }

        $manager->flush();
    }

    /**
     * @param int $timestamp
     * @return bool
     */
    private function isWeekend($timestamp = 0) {
        return (date('N', $timestamp) >= 6);
    }
}