<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * TeamRepository
 */
class TeamRepository extends EntityRepository implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function remove(Team $team)
    {
        $this->container->get('doctrine')->getManager()->getRepository('AppBundle:TimeEntry')->removeByTeam($team);
        $this->_em->remove($team);
        $this->_em->flush();
    }

    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('t')->join('t.memberships', 'm')->where('m.user = ' . $user->getId())->getQuery()->getResult();
    }
}
