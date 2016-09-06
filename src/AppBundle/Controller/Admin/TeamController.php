<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\TeamController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TeamController
 *
 * @package AppBundle\Controller
 *
 * @Route("/admin/team")
 */
class TeamController extends BaseController
{
    /**
     * Lists all existing Team entities.
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/", name="admin_team_index")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return ['pagination' => $this->get('knp_paginator')->paginate(
            $this->getDoctrine()->getManager()->getRepository('AppBundle:Team')->findAll(),
            $request->query->get('page', 1),
            $request->query->get('limit', 10)
        )];
    }
}
