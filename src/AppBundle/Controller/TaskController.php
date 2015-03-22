<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TaskController
 *
 * @package AppBundle\Controller
 *
 * @Route("/task")
 */
class TaskController extends Controller
{
    /**
     * Lists all existing Task entities.
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/", name="task_index")
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     * @Template()
     */
    public function indexAction()
    {
        return ['entities' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Task')->findByTeam($this->getUser()->getActiveTeam())];
    }

    /**
     * Shows an existing Task entity.
     *
     * @param Task $task
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/{id}", requirements={"id": "\d+"}, name="task_show")
     * @Security("is_granted('VIEW', task)")
     * @Template()
     */
    public function showAction(Task $task)
    {
        return ['entity' => $task];
    }

    /**
     * Creates a new Task entity.
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/new", name="task_new")
     * @Security("is_granted('EDIT', user.getActiveTeam())")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $task = new Task();

        $form = $this->get('app.form.factory.task')->createForm(['submit' => ['label' => 'task.template.new.submit']])->setData($task)->handleRequest($request);

        if ($form->isValid()) {
            $task->setTeam($this->getUser()->getActiveTeam());

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'task.flash.created');

            return $this->redirect($this->generateUrl('task_show', ['id' => $task->getId()]));
        }

        return ['entity' => $task, 'form' => $form->createView()];
    }

    /**
     * Edits an existing Task entity.
     *
     * @param Request $request
     * @param Task $task
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="task_edit")
     * @Security("is_granted('EDIT', task)")
     * @Template()
     */
    public function editAction(Request $request, Task $task)
    {
        $form = $this->get('app.form.factory.task')->createForm(['submit' => ['label' => 'task.template.edit.submit']])->setData($task)->handleRequest($request);

        if ($form->isValid()) {
            $task->setTeam($this->getUser()->getActiveTeam());

            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'task.flash.updated');

            return $this->redirect($this->generateUrl('task_show', ['id' => $task->getId()]));
        }

        return ['entity' => $task, 'form' => $form->createView()];
    }

    /**
     * Deletes an existing Task entity.
     *
     * @param Task $task
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET")
     * @Route("/{id}/delete", requirements={"id": "\d+"}, name="task_delete")
     * @Security("is_granted('EDIT', task)")
     */
    public function deleteAction(Task $task)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'task.flash.deleted');

        return $this->redirect($this->generateUrl('task_index'));
    }
}
