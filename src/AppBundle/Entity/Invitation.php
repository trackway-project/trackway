<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="memberships")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     */
    protected $team;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="memberships")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="key", type="string")
     */
    protected $key;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="invitationStatusEnum")
     */
    protected $status;
}