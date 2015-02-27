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
        return [
            'entities' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Team')->findAllByUser($this->getUser())
        ];
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
            $em->flush();

            return $this->redirect($this->generateUrl('team_show', ['id' => $team->getId()]));
        }

        return [
            'entity' => $team,
            'form' => $form->createView()
        ];
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
        return $this->createForm(new TeamFormType(), $team, [
            'action' => $this->generateUrl('team_create'),
            'method' => 'POST'
        ])
            ->remove('memberships')
            ->add('submit', 'submit', ['label' => 'Create']);
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

        return [
            'entity' => $team,
            'form' => $this->createCreateForm($team)->createView()
        ];
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
        return [
            'entity' => $team,
            'delete_form' => $this->createDeleteForm($team)->createView()
        ];
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
        return [
            'entity' => $team,
            'edit_form' => $this->createEditForm($team)->createView(),
            'delete_form' => $this->createDeleteForm($team)->createView()
        ];
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
        return $this->createForm(new TeamFormType(), $team, [
            'action' => $this->generateUrl('team_update', ['id' => $team->getId()]),
            'method' => 'PUT'
        ])->add('submit', 'submit', ['label' => 'Update']);
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
        $form = $this->createEditForm($team);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('team_edit', ['id' => $team->getId()]));
        }

        return [
            'entity' => $team,
            'edit_form' => $form->createView(),
            'delete_form' => $this->createDeleteForm($team)->createView()
        ];
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
        return $this->createFormBuilder()->setAction($this->generateUrl('team_delete', ['id' => $team->getId()]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'Delete'])
            ->getForm();
    }
}
