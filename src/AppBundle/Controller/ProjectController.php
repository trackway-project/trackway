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
     * Lists all existing Project entities.
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/", name="project_index")
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     * @Template()
     */
    public function indexAction()
    {
        return ['entities' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Project')->findAllByTeam($this->getUser()->getActiveTeam())];
    }

    /**
     * Shows an existing Project entity.
     *
     * @param Project $project
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/{id}", requirements={"id": "\d+"}, name="project_show")
     * @Security("is_granted('VIEW', project)")
     * @Template()
     */
    public function showAction(Project $project)
    {
        return ['entity' => $project];
    }

    /**
     * Creates a new Project entity.
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/new", name="project_new")
     * @Security("is_granted('EDIT', user.getActiveTeam())")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $project = new Project();

        $form = $this->createForm(new ProjectFormType(), $project)->add('submit', 'submit', ['label' => 'Create'])->handleRequest($request);

        if ($form->isValid()) {
            $project->setTeam($this->getUser()->getActiveTeam());
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('project_show', ['id' => $project->getId()]));
        }

        return ['entity' => $project, 'form' => $form->createView()];
    }

    /**
     * Edits an existing Project entity.
     *
     * @param Request $request
     * @param Project $project
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="project_edit")
     * @Security("is_granted('EDIT', project)")
     * @Template()
     */
    public function editAction(Request $request, Project $project)
    {
        $form = $this->createForm(new ProjectFormType(), $project)->add('submit', 'submit', ['label' => 'Update'])->handleRequest($request);

        if ($form->isValid()) {
            $project->setTeam($this->getUser()->getActiveTeam());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('project_show', ['id' => $project->getId()]));
        }

        return ['entity' => $project, 'form' => $form->createView()];
    }

    /**
     * Deletes an existing Project entity.
     *
     * @param Project $project
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET")
     * @Route("/{id}/delete", requirements={"id": "\d+"}, name="project_delete")
     * @Security("is_granted('EDIT', project)")
     */
    public function deleteAction(Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();

        return $this->redirect($this->generateUrl('project_index'));
    }
}
