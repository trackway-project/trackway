<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Absence
 *
 * @ORM\Table(name="absences")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\AbsenceRepository")
 */
class Absence extends BaseTimeEntry
{
    /**
     * @var AbsenceReason
     *
     * @ORM\ManyToOne(targetEntity="AbsenceReason")
     * @ORM\JoinColumn(name="reason_id", referencedColumnName="id")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="AbsenceReason")
     */
    protected $reason;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->date->format('Y-m-d') . ' ' . $this->startsAt->format('H:i') . ' ' . $this->endsAt->format('H:i') . ' ' . $this->reason . ' ' . $this->note;
    }

    /**
     * @return AbsenceReason
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param AbsenceReason $reason
     */
    public function setReason(AbsenceReason $reason)
    {
        $this->reason = $reason;
    }
}
