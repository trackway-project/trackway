<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\TimeEntry;
use AppBundle\Form\TimeEntryType;

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
     * @Route("/", name="timeentry")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:TimeEntry')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new TimeEntry entity.
     *
     * @Route("/", name="timeentry_create")
     * @Method("POST")
     * @Template("AppBundle:TimeEntry:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new TimeEntry();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('timeentry_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a TimeEntry entity.
     *
     * @param TimeEntry $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TimeEntry $entity)
    {
        $form = $this->createForm(new TimeEntryType(), $entity, array(
            'action' => $this->generateUrl('timeentry_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new TimeEntry entity.
     *
     * @Route("/new", name="timeentry_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TimeEntry();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a TimeEntry entity.
     *
     * @Route("/{id}", name="timeentry_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:TimeEntry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TimeEntry entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TimeEntry entity.
     *
     * @Route("/{id}/edit", name="timeentry_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:TimeEntry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TimeEntry entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a TimeEntry entity.
    *
    * @param TimeEntry $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TimeEntry $entity)
    {
        $form = $this->createForm(new TimeEntryType(), $entity, array(
            'action' => $this->generateUrl('timeentry_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing TimeEntry entity.
     *
     * @Route("/{id}", name="timeentry_update")
     * @Method("PUT")
     * @Template("AppBundle:TimeEntry:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:TimeEntry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TimeEntry entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('timeentry_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a TimeEntry entity.
     *
     * @Route("/{id}", name="timeentry_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:TimeEntry')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TimeEntry entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('timeentry'));
    }

    /**
     * Creates a form to delete a TimeEntry entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('timeentry_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
