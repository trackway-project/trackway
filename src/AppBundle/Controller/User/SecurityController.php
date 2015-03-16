<?php

namespace AppBundle\Controller\User;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SecurityController
 *
 * @package AppBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Method("GET")
     * @Route("/login", name="security_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $user = new User();
        $user->setUsername($authenticationUtils->getLastUsername());

        // add possible login errors to the flash bag
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            $this->get('session')->getFlashBag()->add('error', $error->getMessage());
        }

        return ['entity' => $user];
    }

    /**
     * Dummy
     *
     * @Route("/login_check", name="security_login_check")
     */
    public function loginCheckAction()
    {
    }

    /**
     * Dummy
     *
     * @Route("/logout", name="security_logout")
     */
    public function logoutCheckAction()
    {
    }
}