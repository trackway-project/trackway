<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Group;
use AppBundle\Entity\Membership;
use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use AppBundle\Controller\TeamController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TeamController
 *
 * @package AppBundle\Controller
 *
 * @Route("/team")
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
    public function indexAction()
    {
        return ['entities' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Team')->findAll()];
    }
}
