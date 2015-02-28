<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Absence
 *
 * @ORM\Table(name="absences")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\AbsenceRepository")
 */
class Absence extends BaseTimeEntry
{
    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="absenceReasonEnum")
     */
    protected $reason;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->date->format('Y-m-d') . ' ' . $this->startsAt->format('H:i') . ' ' . $this->endsAt->format('H:i') . ' ' . $this->reason . ' ' . $this->note;
    }
}
