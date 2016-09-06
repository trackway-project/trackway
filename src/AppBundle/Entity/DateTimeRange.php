<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Embeddable
 */
class DateTimeRange
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="\DateTime")
     */
    protected $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endsAt", type="time")
     */
    protected $endsAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startsAt", type="time")
     */
    protected $startsAt;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->date->format('Y-m-d') . ' ' . $this->startsAt->format('H:i') . ' ' . $this->endsAt->format('H:i');
    }

    /**
     * @return \DateTime
     */
    public function getStartDateTime()
    {
        $return = clone $this->getDate();
        $return->setTime($this->getStartsAt()->format('H'), $this->getStartsAt()->format('i'), $this->getStartsAt()->format('s'));
        return $return;
    }

    /**
     * @return \DateTime
     */
    public function getEndDateTime()
    {
        $return = clone $this->getDate();
        $return->setTime($this->getEndsAt()->format('H'), $this->getEndsAt()->format('i'), $this->getEndsAt()->format('s'));
        return $return;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getEndsAt()
    {
        return $this->endsAt;
    }

    /**
     * @param \DateTime $endsAt
     */
    public function setEndsAt($endsAt)
    {
        $this->endsAt = $endsAt;
    }

    /**
     * @return \DateTime
     */
    public function getStartsAt()
    {
        return $this->startsAt;
    }

    /**
     * @param \DateTime $startsAt
     */
    public function setStartsAt($startsAt)
    {
        $this->startsAt = $startsAt;
    }

    /**
     * @return bool|\DateInterval
     */
    public function getInterval()
    {
        $interval = date_diff($this->getEndDateTime(), $this->getStartDateTime());
        return $interval;
    }

    /**
     * @return int
     */
    public function getIntervalInSeconds()
    {
        return abs((new \DateTime('@0'))->add($this->getInterval())->getTimestamp());
    }
}
