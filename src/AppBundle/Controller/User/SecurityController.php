<?php

namespace AppBundle\Controller\User;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class SecurityController
 *
 * @package AppBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Method("GET")
     * @Route("/login", name="security_login")
     * @Template()
     */
    public function loginAction()
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
     * @Method("POST")
     * @Route("/login_check", name="security_login_check")
     */
    public function loginCheckAction()
    {
    }

    /**
     * Dummy
     *
     * @Method("GET")
     * @Route("/logout", name="security_logout")
     */
    public function logoutCheckAction()
    {
    }
}
