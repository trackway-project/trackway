<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Form\Type\ProjectFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Project controller.
 *
 * @Route("/project")
 */
class ProjectController extends Controller
{
    /**
     * Lists all Project entities.
     *
     * @return array
     *
     * @Route("/", name="project")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('AppBundle:Project')->findAllByTeam($this->getUser()->getActiveTeam());

        return ['entities' => $projects];
    }

    /**
     * Creates a new Project entity.
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/", name="project_create")
     * @Method("POST")
     * @Template("AppBundle:Project:new.html.twig")
     * @Security("is_granted('EDIT', user.getActiveTeam())")
     */
    public function createAction(Request $request)
    {
        $project = new Project();
        $form = $this->createCreateForm($project);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('project_show', ['id' => $project->getId()]));
        }

        return ['entity' => $project, 'form' => $form->createView(),];
    }

    /**
     * Creates a form to create a Project entity.
     *
     * @param Project $project
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(Project $project)
    {
        $form = $this->createForm(new ProjectFormType(), $project, ['action' => $this->generateUrl('project_create'), 'method' => 'POST',]);
        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Displays a form to create a new Project entity.
     *
     * @return array
     *
     * @Route("/new", name="project_new")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('EDIT', user.getActiveTeam())")
     */
    public function newAction()
    {
        $project = new Project();
        $form = $this->createCreateForm($project);

        return ['entity' => $project, 'form' => $form->createView(),];
    }

    /**
     * Finds and displays a Project entity.
     *
     * @param Project $project
     *
     * @return array
     *
     * @Route("/{id}", name="project_show")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('VIEW', project)")
     */
    public function showAction(Project $project)
    {
        $deleteForm = $this->createDeleteForm($project);

        return ['entity' => $project, 'delete_form' => $deleteForm->createView(),];
    }

    /**
     * Displays a form to edit an existing Project entity.
     *
     * @param Project $project
     *
     * @return array
     *
     * @Route("/{id}/edit", name="project_edit")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('EDIT', project)")
     */
    public function editAction(Project $project)
    {
        $editForm = $this->createEditForm($project);
        $deleteForm = $this->createDeleteForm($project);

        return ['entity' => $project, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView(),];
    }

    /**
     * Creates a form to edit a Project entity.
     *
     * @param Project $project
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm(Project $project)
    {
        $form = $this->createForm(new ProjectFormType(), $project, ['action' => $this->generateUrl('project_update', ['id' => $project->getId()]), 'method' => 'PUT',]);
        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * Edits an existing Project entity.
     *
     * @param Request $request
     * @param Project $project
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}", name="project_update")
     * @Method("PUT")
     * @Template("AppBundle:Project:edit.html.twig")
     * @Security("is_granted('EDIT', project)")
     */
    public function updateAction(Request $request, Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($project);
        $editForm = $this->createEditForm($project);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('project_edit', ['id' => $project->getId()]));
        }

        return ['entity' => $project, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView(),];
    }

    /**
     * Deletes a Project entity.
     *
     * @param Request $request
     * @param Project $project
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}", name="project_delete")
     * @Method("DELETE")
     * @Security("is_granted('EDIT', project)")
     */
    public function deleteAction(Request $request, Project $project)
    {
        $form = $this->createDeleteForm($project);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('project'));
    }

    /**
     * Creates a form to delete a Project entity by id.
     *
     * @param Project $project
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Project $project)
    {
        return $this->createFormBuilder()->setAction($this->generateUrl('project_delete', ['id' => $project->getId()]))->setMethod('DELETE')->add('submit', 'submit', ['label' => 'Delete'])->getForm();
    }
}
