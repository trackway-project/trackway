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
     * @var Task
     *
     * @ORM\ManyToOne(targetEntity="Task")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     */
    protected $task;

    /**
     * @return string
     */
    public function __toString()
    {
        return
            $this->date->format('Y-m-d') .
            ' ' .
            $this->startsAt->format('H:i') .
            ' ' .
            $this->endsAt->format('H:i') .
            ' ' .
            $this->project .
            ' ' .
            $this->task .
            ' ' .
            $this->note;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @return Task
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @param Task $task
     */
    public function setTask(Task $task)
    {
        $this->task = $task;
    }
}
