<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Project;
use AppBundle\Entity\Repository\ProjectRepository;
use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends FOSRestController implements ClassResourceInterface
{

    /** @var EntityManager */
    private $manager;

    /**
     * @var ProjectRepository
     */
    private $repo;

    function __construct()
    {
        $this->manager = $this->getDoctrine()->getManager();
        $this->repo = $this->manager->getRepository('AppBundle:Project');
    }


    /**
     * @return array
     * @View()
     * @ApiDoc(
     *   section="Project",
     *   description="Get all Projects for the active Team"
     * )
     *
     */
    public function cgetAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        $projects = $this->repo->findByTeam($user->getActiveTeam());

        return array('projects' => $projects);
    }

    /**
     * @param $projectId
     * @return array
     *
     * @View()
     * @ApiDoc(
     *   section="Project",
     *   description="Get a Project for the active Team"
     * )
     *
     */
    public function getAction($projectId)
    {
        return array(
            'project' => $this->repo->find($projectId),
        );
    }

    /**
     * @param Request $request
     *
     * @View()
     * @ApiDoc(
     *   section="Project",
     *   description="Add a new Project to the active Team"
     * )
     * @return array|\FOS\RestBundle\View\View
     * @internal param Team $team
     */
    public function postAction(Request $request)
    {

    }

    /**
     * @param Request $request
     * @param $projectId
     * @return array|\FOS\RestBundle\View\View
     * @View()
     * @ApiDoc(
     *   section="Project",
     *   description="Edit a Project for the active Team by id"
     * )
     *
     */
    public function putAction(Request $request, $projectId)
    {

    }

    /**
     * @param $projectId
     * @return \FOS\RestBundle\View\View
     *
     * @View()
     * @ApiDoc(
     *   section="Project",
     *   description="Delete a Project for the active Team by id"
     * )
     *
     */
    public function deleteAction($projectId)
    {
        $this->manager->remove($projectId);
        $this->manager->flush();

        return $this->view(null, Codes::HTTP_NO_CONTENT);
    }
}