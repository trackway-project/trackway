<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Absence;
use AppBundle\Entity\Membership;
use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TeamMembershipController
 *
 * @package AppBundle\Controller
 *
 * @Route("/team")
 */
class TeamMembershipController extends Controller
{
    /**
     * Lists all existing Membership entities for the given Team.
     *
     * @param Team $team
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/{id}/membership/", requirements={"id": "\d+"}, name="team_membership_index")
     * @Security("is_granted('EDIT', team)")
     * @Template()
     */
    public function indexAction(Team $team)
    {
        return ['team' => $team, 'entities' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Membership')->findByTeam($team)];
    }

    /**
     * Shows an existing Membership entity.
     *
     * @param Team $team
     * @param Membership $membership
     *
     * @return array
     *
     * @Method("GET")
     * @ParamConverter("team", class="AppBundle:Team", options={"id" = "id"})
     * @ParamConverter("membership", class="AppBundle:Membership", options={"id" = "membershipId"})
     * @Route("/{id}/membership/{membershipId}", requirements={"id": "\d+", "membershipId": "\d+"}, name="team_membership_show")
     * @Security("is_granted('VIEW', membership)")
     * @Template()
     */
    public function showAction(Team $team, Membership $membership)
    {
        dump($team, $membership);
        return ['team' => $team, 'entity' => $membership];
    }
}
