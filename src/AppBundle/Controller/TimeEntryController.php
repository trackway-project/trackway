<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TimeEntry;
use AppBundle\Entity\User;
use AppBundle\Form\Type\TimeEntryFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * TimeEntry controller.
 *
 * @Route("/timeentry")
 */
class TimeEntryController extends Controller
{
    /**
     * Lists all TimeEntry entities.
     *
     * @return array
     *
     * @Route("/", name="timeentry")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        return [
            'entities' => $this->getDoctrine()->getManager()->getRepository('AppBundle:TimeEntry')->findAllByTeamAndUser($user->getActiveTeam(), $user)
        ];
    }

    /**
     * Creates a new TimeEntry entity.
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/", name="timeentry_create")
     * @Method("POST")
     * @Template("AppBundle:TimeEntry:new.html.twig")
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     */
    public function createAction(Request $request)
    {
        $timeEntry = new TimeEntry();
        $form = $this->createCreateForm($timeEntry);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $timeEntry->setTeam($user->getActiveTeam());
            $timeEntry->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($timeEntry);
            $em->flush();

            return $this->redirect($this->generateUrl('timeentry_show', ['id' => $timeEntry->getId()]));
        }

        return [
            'entity' => $timeEntry,
            'form' => $form->createView()
        ];
    }

    /**
     * Creates a form to create a TimeEntry entity.
     *
     * @param TimeEntry $timeEntry
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(TimeEntry $timeEntry)
    {
        return $this->createForm(new TimeEntryFormType(), $timeEntry, [
            'action' => $this->generateUrl('timeentry_create'),
            'method' => 'POST'
        ])->add('submit', 'submit', ['label' => 'Create']);
    }

    /**
     * Displays a form to create a new TimeEntry entity.
     *
     * @return array
     *
     * @Route("/new", name="timeentry_new")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     */
    public function newAction()
    {
        $timeEntry = new TimeEntry();

        return [
            'entity' => $timeEntry,
            'form' => $this->createCreateForm($timeEntry)->createView()
        ];
    }

    /**
     * Finds and displays a TimeEntry entity.
     *
     * @param TimeEntry $timeEntry
     *
     * @return array
     *
     * @Route("/{id}", name="timeentry_show")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('VIEW', timeEntry)")
     */
    public function showAction(TimeEntry $timeEntry)
    {
        return [
            'entity' => $timeEntry,
            'delete_form' => $this->createDeleteForm($timeEntry)->createView()
        ];
    }

    /**
     * Displays a form to edit an existing TimeEntry entity.
     *
     * @param TimeEntry $timeEntry
     *
     * @return array
     *
     * @Route("/{id}/edit", name="timeentry_edit")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('EDIT', timeEntry)")
     */
    public function editAction(TimeEntry $timeEntry)
    {
        return [
            'entity' => $timeEntry,
            'edit_form' => $this->createEditForm($timeEntry)->createView(),
            'delete_form' => $this->createDeleteForm($timeEntry)->createView()
        ];
    }

    /**
     * Creates a form to edit a TimeEntry entity.
     *
     * @param TimeEntry $timeEntry
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm(TimeEntry $timeEntry)
    {
        return $this->createForm(new TimeEntryFormType(), $timeEntry, [
            'action' => $this->generateUrl('timeentry_update', ['id' => $timeEntry->getId()]),
            'method' => 'PUT'
        ])->add('submit', 'submit', ['label' => 'Update']);
    }

    /**
     * Edits an existing TimeEntry entity.
     *
     * @param Request $request
     * @param TimeEntry $timeEntry
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}", name="timeentry_update")
     * @Method("PUT")
     * @Template("AppBundle:TimeEntry:edit.html.twig")
     * @Security("is_granted('EDIT', timeEntry)")
     */
    public function updateAction(Request $request, TimeEntry $timeEntry)
    {
        $form = $this->createEditForm($timeEntry);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $timeEntry->setTeam($user->getActiveTeam());
            $timeEntry->setUser($user);
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('timeentry_edit', ['id' => $timeEntry->getId()]));
        }

        return [
            'entity' => $timeEntry,
            'edit_form' => $form->createView(),
            'delete_form' => $this->createDeleteForm($timeEntry)->createView()
        ];
    }

    /**
     * Deletes a TimeEntry entity.
     *
     * @param Request $request
     * @param TimeEntry $timeEntry
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}", name="timeentry_delete")
     * @Method("DELETE")
     * @Security("is_granted('EDIT', timeEntry)")
     */
    public function deleteAction(Request $request, TimeEntry $timeEntry)
    {
        $form = $this->createDeleteForm($timeEntry);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($timeEntry);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('timeentry'));
    }

    /**
     * Creates a form to delete a TimeEntry entity by id.
     *
     * @param TimeEntry $timeEntry
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(TimeEntry $timeEntry)
    {
        return $this->createFormBuilder()->setAction($this->generateUrl('timeentry_delete', ['id' => $timeEntry->getId()]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'Delete'])
            ->getForm();
    }
}
