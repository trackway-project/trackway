<?php

namespace AppBundle\Controller\User;

use AppBundle\Entity\Membership;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @Route("/", name="profile_membership_index")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return ['pagination' => $this->get('knp_paginator')->paginate(
            $this->getUser()->getMemberships(),
            $request->query->get('page', 1),
            $request->query->get('limit', 10)
        )];
    }

    /**
     * Shows an existing Membership entity.
     *
     * @param Membership $membership
     *
     * @return array
     *
     * @Method("GET")
     * @ParamConverter("membership", class="AppBundle:Membership", options={"id" = "membershipId"})
     * @Route("/{membershipId}", requirements={"membershipId": "\d+"}, name="profile_membership_show")
     * @Security("is_granted('VIEW', membership)")
     * @Template()
     */
    public function showAction(Membership $membership)
    {
        return ['entity' => $membership];
    }

    /**
     * Deletes an existing Membership entity.
     *
     * @param Membership $membership
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET")
     * @ParamConverter("membershipId", class="AppBundle:Membership", options={"id" = "membershipId"})
     * @Route("/{membershipId}/delete", requirements={"membershipId": "\d+"}, name="profile_membership_delete")
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
