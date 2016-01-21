<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Membership;
use AppBundle\Entity\Team;
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
    public function indexAction(Request $request, Team $team)
    {
        return ['pagination' => $this->get('knp_paginator')->paginate(
            $this->getDoctrine()->getManager()->getRepository('AppBundle:Membership')->findByTeamQuery($team),
            $request->query->get('page', 1),
            $request->query->get('limit', 10)
        )];
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
        return ['team' => $team, 'entity' => $membership];
    }

    /**
     * Edits an existing Membership entity.
     *
     * @param Request $request
     * @param Team $team
     * @param Membership $membership
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @ParamConverter("team", class="AppBundle:Team", options={"id" = "id"})
     * @ParamConverter("membership", class="AppBundle:Membership", options={"id" = "membershipId"})
     * @Route("/{id}/membership/{membershipId}/edit", requirements={"id": "\d+", "membershipId": "\d+"}, name="team_membership_edit")
     * @Security("is_granted('EDIT', membership)")
     * @Template()
     */
    public function editAction(Request $request, Team $team, Membership $membership)
    {
        $form =
            $this->get('app.form.factory.membership')
                ->createForm(['submit' => ['label' => 'teamMembership.template.edit.submit']])
                ->remove('team')
                ->remove('user')
                ->setData($membership)
                ->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'teamMembership.flash.updated');

            return $this->redirect($this->generateUrl('team_membership_show', ['id' => $team->getId(), 'membershipId' => $membership->getId()]));
        }

        return ['team' => $team, 'entity' => $membership, 'form' => $form->createView()];
    }

    /**
     * Deletes an existing Membership entity.
     *
     * @param Team $team
     * @param Membership $membership
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET")
     * @ParamConverter("team", class="AppBundle:Team", options={"id" = "id"})
     * @ParamConverter("membership", class="AppBundle:Membership", options={"id" = "membershipId"})
     * @Route("/{id}/membership/{membershipId}/delete", requirements={"id": "\d+", "membershipId": "\d+"}, name="team_membership_delete")
     * @Security("is_granted('EDIT', membership)")
     */
    public function deleteAction(Team $team, Membership $membership)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($membership);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'teamMembership.flash.deleted');

        return $this->redirect($this->generateUrl('team_membership_index'));
    }
}
