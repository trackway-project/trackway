<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Entity\Invitation;
use AppBundle\Entity\Membership;
use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        return ['pagination' => $this->get('knp_paginator')->paginate(
            $this->getDoctrine()->getManager()->getRepository('AppBundle:Invitation')->findByTeamQuery($team),
            $this->get('request')->query->get('page', 1),
            $this->get('request')->query->get('limit', 10)
        )];
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

        $form =
            $this->get('app.form.factory.invitation')
                ->createForm(['submit' => ['label' => 'invitation.template.invite.submit']])
                ->remove('team')
                ->remove('status')
                ->setData($invitation)
                ->handleRequest($request);

        if ($form->isValid()) {
            if ($this->getDoctrine()
                    ->getRepository('AppBundle:Invitation')
                    ->createQueryBuilder('i')
                    ->select('count(i.id)')
                    ->where('i.email = ?1')
                    ->andWhere('i.id != ?2')
                    ->setParameters([1 => $invitation->getEmail(), 2 => $invitation->getId()])
                    ->getQuery()
                    ->getFirstResult() > 0
            ) {
                $this->get('session')->getFlashBag()->add('success', 'invitation.flash.alreadyInvited');

                return ['entity' => $team, 'form' => $form->createView()];
            }

            $invitation->setTeam($team);
            $invitation->setStatus($this->getDoctrine()->getRepository('AppBundle:InvitationStatus')->findOneByName('open'));
            $invitation->setConfirmationToken(md5(uniqid(mt_rand(), true)));

            $em = $this->getDoctrine()->getManager();
            $em->persist($invitation);
            $em->flush();

            // Send mail
            $mailer = $this->get('mailer');
            $message =
                $mailer->createMessage()
                    ->setSubject('You were invited!')
                    ->setFrom('no-reply@trackway.org')
                    ->setTo($invitation->getEmail())
                    ->setBody($this->renderView('@App/TeamInvitation/email.html.twig', ['entity' => $invitation]), 'text/html');
            $mailer->send($message);

            $this->get('session')->getFlashBag()->add('success', 'invitation.flash.invited');

            return $this->redirect($this->generateUrl('team_invitation_show', ['id' => $team->getId(), 'invitationId' => $invitation->getId()]));
        }

        return ['entity' => $team, 'form' => $form->createView()];
    }

    /**
     * @param $token
     *
     * @return RedirectResponse
     * @throws NotFoundHttpException
     *
     * @Method("GET")
     * @Route("/invitation/{token}/accept", requirements={"token": "[a-zA-Z0-9]+"}, name="team_invitation_accept")
     */
    public function acceptAction($token)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var $user User */
        $user = $this->getUser();

        /** @var Group $group */
        $group = $em->getRepository('AppBundle:Group')->findOneByName('user');

        if (!$group) {
            throw $this->createNotFoundException('Unable to find Group entity.');
        }

        /** @var Invitation $invitation */
        $invitation = $em->getRepository('AppBundle:Invitation')->findOneBy(['confirmationToken' => $token]);
        $invitation->setConfirmationToken(null);
        $invitation->setStatus($em->getRepository('AppBundle:InvitationStatus')->findOneByName('accepted'));
        $invitation->setUser($this->getUser());

        $membership = new Membership();
        $membership->setGroup($group);
        $membership->setTeam($invitation->getTeam());
        $membership->setUser($user);

        if (!$user->getActiveTeam()) {
            $user->setActiveTeam($invitation->getTeam());
            $em->persist($user);
        }

        $em->persist($invitation);
        $em->persist($membership);

        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'invitation.flash.accepted');

        return $this->redirect($this->generateUrl('calendar_index'));
    }

    /**
     * @param $token
     *
     * @return RedirectResponse
     * @throws NotFoundHttpException
     *
     * @Method("GET")
     * @Route("/invitation/{token}/reject", requirements={"token": "[a-zA-Z0-9]+"}, name="team_invitation_reject")
     */
    public function rejectAction($token)
    {
        /** @var Invitation $invitation */
        $invitation = $this->getDoctrine()->getManager()->getRepository('AppBundle:Invitation')->findOneBy(['confirmationToken' => $token]);

        $invitation->setStatus($this->getDoctrine()->getManager()->getRepository('AppBundle:InvitationStatus')->findOneByName('rejected'));

        $this->getDoctrine()->getManager()->flush();

        $this->get('session')->getFlashBag()->add('success', 'invitation.flash.rejected');

        return $this->redirect($this->generateUrl('calendar_index'));
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

        $this->get('session')->getFlashBag()->add('success', 'invitation.flash.deleted');

        return $this->redirect($this->generateUrl('team_invitation_index', ['id' => $team->getId()]));
    }
}
