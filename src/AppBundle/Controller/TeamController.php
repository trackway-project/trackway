<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Entity\Membership;
use AppBundle\Entity\Team;
use AppBundle\Form\Type\TeamFormType;
use FOS\UserBundle\Model\GroupManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Team controller.
 *
 * @Route("/team")
 */
class TeamController extends Controller
{
    /**
     * Lists all Team entities.
     *
     * @return array
     *
     * @Route("/", name="team")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $teams = $em->getRepository('AppBundle:Team')->findAllByUser($this->getUser());

        return ['entities' => $teams,];
    }

    /**
     * Creates a new Team entity.
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/", name="team_create")
     * @Method("POST")
     * @Template("AppBundle:Team:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $team = new Team();
        $form = $this->createCreateForm($team);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var GroupManager $groupManager */
            $groupManager = $this->get('fos_user.group_manager');
            /** @var Group $group */
            $group = $groupManager->findGroupByName('Owner');

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
            $em->flush();

            return $this->redirect($this->generateUrl('team_show', ['id' => $team->getId()]));
        }

        return ['entity' => $team, 'form' => $form->createView(),];
    }

    /**
     * Creates a form to create a Team entity.
     *
     * @param Team $team
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(Team $team)
    {
        $form = $this->createForm(new TeamFormType(), $team, ['action' => $this->generateUrl('team_create'), 'method' => 'POST',]);
        $form->remove('memberships');
        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Displays a form to create a new Team entity.
     *
     * @return array
     *
     * @Route("/new", name="team_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $team = new Team();
        $form = $this->createCreateForm($team);

        return ['entity' => $team, 'form' => $form->createView(),];
    }

    /**
     * Finds and displays a Team entity.
     *
     * @param Team $team
     *
     * @return array
     *
     * @Route("/{id}", name="team_show")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('VIEW', team)")
     */
    public function showAction(Team $team)
    {
        $deleteForm = $this->createDeleteForm($team);

        return ['entity' => $team, 'delete_form' => $deleteForm->createView(),];
    }

    /**
     * Displays a form to edit an existing Team entity.
     *
     * @param Team $team
     *
     * @return array
     *
     * @Route("/{id}/edit", name="team_edit")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('EDIT', team)")
     */
    public function editAction(Team $team)
    {
        $editForm = $this->createEditForm($team);
        $deleteForm = $this->createDeleteForm($team);

        return ['entity' => $team, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView(),];
    }

    /**
     * Creates a form to edit a Team entity.
     *
     * @param Team $team
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm(Team $team)
    {
        $form = $this->createForm(new TeamFormType(), $team, ['action' => $this->generateUrl('team_update', ['id' => $team->getId()]), 'method' => 'PUT',]);
        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * Edits an existing Team entity.
     *
     * @param Request $request
     * @param Team $team
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}", name="team_update")
     * @Method("PUT")
     * @Template("AppBundle:Team:edit.html.twig")
     * @Security("is_granted('EDIT', team)")
     */
    public function updateAction(Request $request, Team $team)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($team);
        $editForm = $this->createEditForm($team);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('team_edit', ['id' => $team->getId()]));
        }

        return ['entity' => $team, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView(),];
    }

    /**
     * Deletes a Team entity.
     *
     * @param Request $request
     * @param Team $team
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}", name="team_delete")
     * @Method("DELETE")
     * @Security("is_granted('EDIT', team)")
     */
    public function deleteAction(Request $request, Team $team)
    {
        $form = $this->createDeleteForm($team);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($team);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('team'));
    }

    /**
     * Creates a form to delete a Team entity by id.
     *
     * @param Team $team
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Team $team)
    {
        return $this->createFormBuilder()->setAction($this->generateUrl('team_delete', ['id' => $team->getId()]))->setMethod('DELETE')->add('submit', 'submit', ['label' => 'Delete'])->getForm();
    }
}
