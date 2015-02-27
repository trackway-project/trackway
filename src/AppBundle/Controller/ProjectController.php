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
        return [
            'entities' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Project')->findAllByTeam($this->getUser()->getActiveTeam())
        ];
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
            $project->setTeam($this->getUser()->getActiveTeam());
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('project_show', ['id' => $project->getId()]));
        }

        return [
            'entity' => $project,
            'form' => $form->createView()
        ];
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
        return $this->createForm(new ProjectFormType(), $project, [
            'action' => $this->generateUrl('project_create'),
            'method' => 'POST'
        ])->add('submit', 'submit', ['label' => 'Create']);
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

        return [
            'entity' => $project,
            'form' => $this->createCreateForm($project)->createView()
        ];
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
        return [
            'entity' => $project,
            'delete_form' => $this->createDeleteForm($project)->createView()
        ];
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
        return [
            'entity' => $project,
            'edit_form' => $this->createEditForm($project)->createView(),
            'delete_form' => $this->createDeleteForm($project)->createView()
        ];
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
        return $this->createForm(new ProjectFormType(), $project, [
            'action' => $this->generateUrl('project_update', ['id' => $project->getId()]),
            'method' => 'PUT'
        ])->add('submit', 'submit', ['label' => 'Update']);
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
        $form = $this->createEditForm($project);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $project->setTeam($this->getUser()->getActiveTeam());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('project_edit', ['id' => $project->getId()]));
        }

        return [
            'entity' => $project,
            'edit_form' => $form->createView(),
            'delete_form' => $this->createDeleteForm($project)->createView()
        ];
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
        return $this->createFormBuilder()->setAction($this->generateUrl('project_delete', ['id' => $project->getId()]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'Delete'])
            ->getForm();
    }
}
