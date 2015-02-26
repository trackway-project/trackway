<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\Type\TaskFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Task controller.
 *
 * @Route("/task")
 */
class TaskController extends Controller
{
    /**
     * Lists all Task entities.
     *
     * @return array
     *
     * @Route("/", name="task")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('AppBundle:Task')->findAllByTeam($this->getUser()->getActiveTeam());

        return ['entities' => $tasks,];
    }

    /**
     * Creates a new Task entity.
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/", name="task_create")
     * @Method("POST")
     * @Template("AppBundle:Task:new.html.twig")
     * @Security("is_granted('EDIT', user.getActiveTeam())")
     */
    public function createAction(Request $request)
    {
        $task = new Task();
        $form = $this->createCreateForm($task);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirect($this->generateUrl('task_show', ['id' => $task->getId()]));
        }

        return ['entity' => $task, 'form' => $form->createView(),];
    }

    /**
     * Creates a form to create a Task entity.
     *
     * @param Task $task
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(Task $task)
    {
        $form = $this->createForm(new TaskFormType(), $task, ['action' => $this->generateUrl('task_create'), 'method' => 'POST',]);
        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Displays a form to create a new Task entity.
     *
     * @return array
     *
     * @Route("/new", name="task_new")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('EDIT', user.getActiveTeam())")
     */
    public function newAction()
    {
        $task = new Task();
        $form = $this->createCreateForm($task);

        return ['entity' => $task, 'form' => $form->createView(),];
    }

    /**
     * Finds and displays a Task entity.
     *
     * @param Task $task
     *
     * @return array
     *
     * @Route("/{id}", name="task_show")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('VIEW', task)")
     */
    public function showAction(Task $task)
    {
        $deleteForm = $this->createDeleteForm($task);

        return ['entity' => $task, 'delete_form' => $deleteForm->createView(),];
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     * @param Task $task
     *
     * @return array
     *
     * @Route("/{id}/edit", name="task_edit")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('EDIT', task)")
     */
    public function editAction(Task $task)
    {
        $editForm = $this->createEditForm($task);
        $deleteForm = $this->createDeleteForm($task);

        return ['entity' => $task, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView(),];
    }

    /**
     * Creates a form to edit a Task entity.
     *
     * @param Task $task
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm(Task $task)
    {
        $form = $this->createForm(new TaskFormType(), $task, ['action' => $this->generateUrl('task_update', ['id' => $task->getId()]), 'method' => 'PUT',]);
        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * Edits an existing Task entity.
     *
     * @param Request $request
     * @param Task $task
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}", name="task_update")
     * @Method("PUT")
     * @Template("AppBundle:Task:edit.html.twig")
     * @Security("is_granted('EDIT', task)")
     */
    public function updateAction(Request $request, Task $task)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($task);
        $editForm = $this->createEditForm($task);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('task_edit', ['id' => $task->getId()]));
        }

        return ['entity' => $task, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView(),];
    }

    /**
     * Deletes a Task entity.
     *
     * @param Request $request
     * @param Task $task
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}", name="task_delete")
     * @Method("DELETE")
     * @Security("is_granted('EDIT', task)")
     */
    public function deleteAction(Request $request, Task $task)
    {
        $form = $this->createDeleteForm($task);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('task'));
    }

    /**
     * Creates a form to delete a Task entity by id.
     *
     * @param Task $task
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Task $task)
    {
        return $this->createFormBuilder()->setAction($this->generateUrl('task_delete', ['id' => $task->getId()]))->setMethod('DELETE')->add('submit', 'submit', ['label' => 'Delete'])->getForm();
    }
}
