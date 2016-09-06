<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TimeEntry
 *
 * @ORM\Table(name="timeEntries")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\TimeEntryRepository")
 */
class TimeEntry extends BaseTimeEntry
{
    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->dateTimeRange . ' ' . $this->project . ' ' . $this->note;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project|null $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }
}
