<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Invitation
 *
 * @ORM\Table(name="invitations")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\InvitationRepository")
 */
class Invitation
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="invitations")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="Team")
     */
    protected $team;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Email()
     * @Assert\Length(max = 255)
     */
    protected $email;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="invitations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="key", type="string", length=32)
     */
    protected $key;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="invitationStatusEnum")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    protected $status;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->team . ' ' . $this->user;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
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
    public function setTeam($team)
    {
        $this->team = $team;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}