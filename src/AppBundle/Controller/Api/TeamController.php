<?php


namespace AppBundle\Controller\Api;

use AppBundle\Entity\Team;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TeamController
 * @RouteResource("Team")
 */
class TeamController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @View()
     * @ApiDoc(
     *   section="Team",
     *   description="Get all Teams"
     * )
     * @return mixed
     */
    public function cgetAction()
    {
        return array(
            'teams' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Team')->findAll()
        );
    }

    /**
     * @param $teamId
     *
     * @View()
     * @ApiDoc(
     *   section="Team",
     *   description="Get a Team by id"
     * )
     *
     * @return array
     *
     * @author Felix Peters <info@wichteldesign.de>
     */
    public function getAction($teamId)
    {
        $team = $this->getTeam($teamId);

        return array(
            'team' => $team,
        );
    }

    /**
     * Get entity instance
     * @var integer $id Id of the project
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return Team
     */
    protected function getTeam($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Team')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find team');
        }

        return $entity;
    }
}