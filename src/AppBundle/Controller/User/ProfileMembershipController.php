<?php

namespace AppBundle\Controller\User;

use AppBundle\Entity\Membership;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProfileMembershipController
 *
 * @package AppBundle\Controller
 *
 * @Route("/profile/membership")
 */
class ProfileMembershipController extends Controller
{

    /**
     * Lists all existing Membership entities for the current user.
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/", requirements={"id": "\d+"}, name="profile_membership_index")
     * @Template()
     */
    public function indexAction()
    {
        return ['entities' => $this->getUser()->getMemberships()];
    }

    /**
     * Shows an existing Membership entity.
     *
     * @param Membership $membership
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/{id}", requirements={"id": "\d+"}, name="profile_membership_show")
     * @Security("is_granted('VIEW', membership)")
     * @Template()
     */
    public function showAction(Membership $membership)
    {
        return ['entity' => $membership];
    }

    /**
     * Edits an existing Membership entity.
     *
     * @param Request $request
     * @param Membership $membership
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="profile_membership_edit")
     * @Security("is_granted('EDIT', membership)")
     * @Template()
     */
    public function editAction(Request $request, Membership $membership)
    {
        $form = $this
            ->get('app.form.factory.membership')
            ->createForm([
                'submit' => ['label' => 'Update']
            ])
            ->remove('team')
            ->remove('user')
            ->setData($membership)
            ->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'profile_membership.flash.updated');

            return $this->redirect($this->generateUrl('profile_membership_show', ['id' => $membership->getId()]));
        }

        return ['entity' => $membership, 'form' => $form->createView()];
    }

    /**
     * Deletes an existing Membership entity.
     *
     * @param Membership $membership
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET")
     * @Route("/{id}/delete", requirements={"id": "\d+"}, name="profile_membership_delete")
     * @Security("is_granted('EDIT', membership)")
     */
    public function deleteAction(Membership $membership)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($membership);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'profile_membership.flash.deleted');

        return $this->redirect($this->generateUrl('profile_membership_index'));
    }
}
