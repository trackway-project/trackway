<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GroupController
 *
 * @package AppBundle\Controller
 *
 * @Route("/admin/group")
 */
class UserController extends Controller
{
    /**
     * Lists all existing User entities.
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/", name="admin_user_index")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return ['pagination' => $this->get('knp_paginator')->paginate(
            $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findAll(),
            $request->query->get('page', 1),
            $request->query->get('limit', 10)
        )];
    }

    /**
     * Shows an existing User entity.
     *
     * @param User $user
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/{id}", requirements={"id": "\d+"}, name="admin_user_show")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function showAction(User $user)
    {
        return ['entity' => $user];
    }

    /**
     * Creates a new User entity.
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/new", name="admin_user_new")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $user = new User();

        $form =
            $this->get('app.form.factory.user')
                ->createForm(['submit' => ['label' => 'admin.user.template.new.submit']])
                ->setData($user)
                ->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'user.flash.created');

            return $this->redirect($this->generateUrl('admin_user_show', ['id' => $user->getId()]));
        }

        return ['entity' => $user, 'form' => $form->createView()];
    }

    /**
     * Edits an existing User entity.
     *
     * @param Request $request
     * @param User $user
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="admin_user_edit")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function editAction(Request $request, User $user)
    {
        $form =
            $this->get('app.form.factory.user')
                ->createForm(['submit' => ['label' => 'admin.user.template.edit.submit']])
                ->setData($user)
                ->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'user.flash.updated');

            return $this->redirect($this->generateUrl('admin_user_show', ['id' => $user->getId()]));
        }

        return ['entity' => $user, 'form' => $form->createView()];
    }

    /**
     * Deletes a User entity.
     *
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET")
     * @Route("/{id}/delete", requirements={"id": "\d+"}, name="admin_user_delete")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'user.flash.deleted');

        return $this->redirect($this->generateUrl('admin_user_index'));
    }
}
