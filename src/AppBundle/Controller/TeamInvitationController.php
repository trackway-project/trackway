<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Absence;
use AppBundle\Entity\Invitation;
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
 * Class TeamInvitationController
 *
 * @package AppBundle\Controller
 *
 * @Route("/team")
 */
class TeamInvitationController extends Controller
{
    /**
     * Lists all existing Invitation entities for the given Team.
     *
     * @param Team $team
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/{id}/invitation/", requirements={"id": "\d+"}, name="team_invitation_index")
     * @Security("is_granted('EDIT', team)")
     * @Template()
     */
    public function indexAction(Team $team)
    {
        return ['team' => $team, 'entities' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Invitation')->findByTeam($team)];
    }

    /**
     * Invite a user to the given Team.
     *
     * @param Request $request
     * @param Team $team
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/{id}/invite", requirements={"id": "\d+"}, name="team_invitation_invite")
     * @Security("is_granted('EDIT', team)")
     * @Template()
     */
    public function inviteAction(Request $request, Team $team)
    {
        $invitation = new Invitation();

        $form = $this
            ->get('app.form.factory.invitation')
            ->createForm([
                'submit' => ['label' => 'Invite']
            ])
            ->remove('team')
            ->remove('status')
            ->setData($invitation)
            ->handleRequest($request);

        if ($form->isValid()) {
            $invitation->setTeam($team);
            $invitation->setStatus('open');
            $invitation->setConfirmationToken($this->get('fos_user.util.token_generator')->generateToken());

            $em = $this->getDoctrine()->getManager();
            $em->persist($invitation);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'team_invitation.flash.invited');

            return $this->redirect($this->generateUrl('team_invitation_show', ['id' => $team->getId(), 'invitationId' => $invitation->getId()]));
        }

        return ['entity' => $team, 'form' => $form->createView()];
    }

    /**
     * Shows an existing Invitation entity.
     *
     * @param Team $team
     * @param Invitation $invitation
     *
     * @return array
     *
     * @Method("GET")
     * @ParamConverter("team", class="AppBundle:Team", options={"id" = "id"})
     * @ParamConverter("invitation", class="AppBundle:Invitation", options={"id" = "invitationId"})
     * @Route("/{id}/invitation/{invitationId}", requirements={"id": "\d+", "invitationId": "\d+"}, name="team_invitation_show")
     * @Security("is_granted('VIEW', team)")
     * @Template()
     */
    public function showAction(Team $team, Invitation $invitation)
    {
        return ['team' => $team, 'entity' => $invitation];
    }

    /**
     * Edits an existing Invitation entity.
     *
     * @param Request $request
     * @param Team $team
     * @param Invitation $invitation
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @ParamConverter("team", class="AppBundle:Team", options={"id" = "id"})
     * @ParamConverter("invitation", class="AppBundle:Invitation", options={"id" = "invitationId"})
     * @Route("/{id}/invitation/{invitationId}/edit", requirements={"id": "\d+", "invitationId": "\d+"}, name="team_invitation_edit")
     * @Security("is_granted('EDIT', team)")
     * @Template()
     */
    public function editAction(Request $request, Team $team, Invitation $invitation)
    {
        $form = $this
            ->get('app.form.factory.invitation')
            ->createForm([
                'submit' => ['label' => 'Update']
            ])
            ->remove('team')
            ->setData($invitation)
            ->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'team_invitation.flash.updated');

            return $this->redirect($this->generateUrl('team_invitation_show', ['id' => $team->getId(), 'invitationId' => $invitation->getId()]));
        }

        return ['team' => $team, 'entity' => $invitation, 'form' => $form->createView()];
    }

    /**
     * Deletes an existing Invitation entity.
     *
     * @param Team $team
     * @param Invitation $invitation
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET")
     * @ParamConverter("team", class="AppBundle:Team", options={"id" = "id"})
     * @ParamConverter("invitation", class="AppBundle:Invitation", options={"id" = "invitationId"})
     * @Route("/{id}/invitation/{invitationId}/delete", requirements={"id": "\d+", "invitationId": "\d+"}, name="team_invitation_delete")
     * @Security("is_granted('EDIT', team)")
     */
    public function deleteAction(Team $team, Invitation $invitation)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($invitation);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'team_invitation.flash.deleted');

        return $this->redirect($this->generateUrl('team_invitation_index'));
    }
}
