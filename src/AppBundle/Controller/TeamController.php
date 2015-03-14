<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Entity\Membership;
use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TeamController
 *
 * @package AppBundle\Controller
 *
 * @Route("/team")
 */
class TeamController extends Controller
{
    /**
     * Lists all existing Team entities.
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/", name="team_index")
     * @Template()
     */
    public function indexAction()
    {
        return ['entities' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Team')->findByUser($this->getUser())];
    }

    /**
     * Shows an existing Team entity.
     *
     * @param Team $team
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/{id}", requirements={"id": "\d+"}, name="team_show")
     * @Security("is_granted('VIEW', team)")
     * @Template()
     */
    public function showAction(Team $team)
    {
        return ['entity' => $team];
    }

    /**
     * Creates a new Team entity.
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/new", name="team_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $team = new Team();

        $form = $this
            ->get('app.form.factory.team')
            ->createForm([
                'submit' => ['label' => 'Create']
            ])
            ->remove('memberships')
            ->setData($team)
            ->handleRequest($request);

        if ($form->isValid()) {
            /** @var Group $group */
            $group = $this->get('fos_user.group_manager')->findGroupByName('Owner');

            if (!$group) {
                throw $this->createNotFoundException('Unable to find Group entity.');
            }

            $membership = new Membership();
            $membership->setTeam($team);
            $membership->setUser($this->getUser());
            $membership->setGroup($group);

            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->persist($membership);

            /** @var $user User */
            $user = $this->getUser();
            if (!$user->getActiveTeam()) {
                $user->setActiveTeam($team);
                $em->persist($user);
            }

            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'team.flash.created');

            return $this->redirect($this->generateUrl('team_show', ['id' => $team->getId()]));
        }

        return ['entity' => $team, 'form' => $form->createView()];
    }

    /**
     * Edits an existing Team entity.
     *
     * @param Request $request
     * @param Team $team
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="team_edit")
     * @Security("is_granted('EDIT', team)")
     * @Template()
     */
    public function editAction(Request $request, Team $team)
    {
        $form = $this
            ->get('app.form.factory.team')
            ->createForm([
                'submit' => ['label' => 'Update']
            ])
            ->remove('memberships')
            ->setData($team)
            ->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'team.flash.updated');

            return $this->redirect($this->generateUrl('team_show', ['id' => $team->getId()]));
        }

        return ['entity' => $team, 'form' => $form->createView()];
    }

    /**
     * Deletes an existing Team entity.
     *
     * @param Team $team
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET")
     * @Route("/{id}/delete", requirements={"id": "\d+"}, name="team_delete")
     * @Security("is_granted('EDIT', team)")
     */
    public function deleteAction(Team $team)
    {
        $this->getDoctrine()->getManager()->getRepository('AppBundle:Team')->remove($team);

        $this->get('session')->getFlashBag()->add('success', 'team.flash.deleted');

        return $this->redirect($this->generateUrl('team_index'));
    }
}
