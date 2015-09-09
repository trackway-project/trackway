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
 *
 * @package AppBundle\Controller\Api
 *
 * @RouteResource("Team")
 */
class TeamController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @return array
     *
     * @View()
     * @ApiDoc(
     *   section="Team",
     *   description="Get all Teams"
     * )
     */
    public function cgetAction()
    {
        return [
            'teams' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Team')->findAll()
        ];
    }

    /**
     * @param $teamId
     *
     * @return array
     *
     * @View()
     * @ApiDoc(
     *   section="Team",
     *   description="Get a Team by id"
     * )
     *
     * @author Felix Peters <info@wichteldesign.de>
     */
    public function getAction($teamId)
    {
        return [
            'team' => $this->getTeam($teamId)
        ];
    }

    /**
     * Get entity instance
     *
     * @param integer $id Id of the project
     *
     * @return Team
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
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
