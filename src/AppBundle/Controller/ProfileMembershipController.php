<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Absence;
use AppBundle\Entity\Membership;
use AppBundle\Entity\Team;
use AppBundle\Entity\User;
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
}
