<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Group;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class GroupController
 *
 * @package AppBundle\Controller
 *
 * @Route("/admin/group")
 */
class GroupController extends Controller
{
    /**
     * Lists all existing Group entities.
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/", name="admin_group_index")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function indexAction()
    {
        return ['entities' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Group')->findAll()];
    }

    /**
     * Shows an existing Group entity.
     *
     * @param Group $group
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/{id}", requirements={"id": "\d+"}, name="admin_group_show")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function showAction(Group $group)
    {
        return ['entity' => $group];
    }

    /**
     * Creates a new Group entity.
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/new", name="admin_group_new")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $group = new Group();

        $form = $this
            ->get('app.form.factory.group')
            ->createForm([
                'submit' => ['label' => 'Create']
            ])
            ->setData($group)
            ->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'group.flash.created');

            return $this->redirect($this->generateUrl('admin_group_show', ['id' => $group->getId()]));
        }

        return ['entity' => $group, 'form' => $form->createView()];
    }

    /**
     * Edits an existing Group entity.
     *
     * @param Request $request
     * @param Group $group
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="admin_group_edit")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function editAction(Request $request, Group $group)
    {
        $form = $this
            ->get('app.form.factory.group')
            ->createForm([
                'submit' => ['label' => 'Update']
            ])
            ->setData($group)
            ->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'group.flash.updated');

            return $this->redirect($this->generateUrl('admin_group_show', ['id' => $group->getId()]));
        }

        return ['entity' => $group, 'form' => $form->createView()];
    }

    /**
     * Deletes a Group entity.
     *
     * @param Group $group
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET")
     * @Route("/{id}/delete", requirements={"id": "\d+"}, name="admin_group_delete")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteAction(Group $group)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($group);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'group.flash.deleted');

        return $this->redirect($this->generateUrl('admin_group_index'));
    }
}
