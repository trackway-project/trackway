<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Membership
 *
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\MembershipRepository")
 * @ORM\Table(name="memberships")
 */
class Membership
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="memberships")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="Team")
     */
    protected $team;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="memberships")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="User")
     */
    protected $user;

    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="Group")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="Group")
     */
    protected $group;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->team . ' ' . $this->user . ' (' . $this->group . ')';
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param Team $team
     */
    public function setTeam(Team $team)
    {
        $this->team = $team;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param Group $group
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;
    }
}