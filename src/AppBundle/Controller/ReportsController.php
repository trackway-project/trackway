<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Absence;
use AppBundle\Entity\TimeEntry;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ReportsController
 *
 * @package AppBundle\Controller
 *
 * @Route("/reports")
 */
class ReportsController extends Controller
{
    /**
     * @param Request $request
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/timeentry", name="timeentry_report")
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     * @Template()
     */
    public function timeentryAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $startDate = new \DateTime($request->query->get('start', 'now'));
        $startDate->setTime(0, 0);
        $endDate = new \DateTime($request->query->get('end', 'now'));
        $endDate->setTime(0, 0);

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'pagination' => $this->get('knp_paginator')->paginate(
                $this->getDoctrine()->getManager()->getRepository('AppBundle:TimeEntry')->findByTeamAndUserQuery(
                    $user->getActiveTeam(),
                    $user,
                    $startDate,
                    $endDate),
                $request->query->get('page', 1),
                $request->query->get('limit', 10)
            )];
    }

    /**
     * @param Request $request
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/absence", name="absence_report")
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     * @Template()
     */
    public function absenceAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $startDate = new \DateTime($request->query->get('start', 'now'));
        $startDate->setTime(0, 0);
        $endDate = new \DateTime($request->query->get('end', 'now'));
        $endDate->setTime(0, 0);

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'pagination' => $this->get('knp_paginator')->paginate(
                $this->getDoctrine()->getManager()->getRepository('AppBundle:Absence')->findByTeamAndUserQuery(
                    $user->getActiveTeam(),
                    $user,
                    $startDate,
                    $endDate),
                $request->query->get('page', 1),
                $request->query->get('limit', 10)
            )];
    }

    /**
     * @Method("GET")
     * @Route("/statistics", name="statistics")
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     * @param Request $request
     * @return JsonResponse
     */
    public function statisticsAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $startDate = new \DateTime($request->query->get('start', 'now'));
        $startDate->setTime(0, 0);
        $endDate = new \DateTime($request->query->get('end', 'now'));
        $endDate->setTime(0, 0);

        $timeEntryResult = $this->getDoctrine()->getManager()->getRepository('AppBundle:TimeEntry')->findByTeamAndUserQuery(
            $user->getActiveTeam(),
            $user,
            $startDate,
            $endDate)->getResult();
        $absenceResult = $this->getDoctrine()->getManager()->getRepository('AppBundle:Absence')->findByTeamAndUserQuery(
            $user->getActiveTeam(),
            $user,
            $startDate,
            $endDate)->getResult();

        $return = [];

        /** @var TimeEntry $entry */
        foreach ($timeEntryResult as $entry) {
            $date = $entry->getDateTimeRange()->getDate()->format('Y-m-d');
            if (!isset($return[$date])) {
                $statistic = ['entry' => 0];
                $return[$date] = $statistic;
            }
            $return[$date]['entry'] += $entry->getDateTimeRange()->getInterval()->h;
            $return[$date]['entry'] += ($entry->getDateTimeRange()->getInterval()->i) / 60;
        }

        /** @var Absence $entry */
        foreach ($absenceResult as $entry) {
            $date = $entry->getDateTimeRange()->getDate()->format('Y-m-d');
            if (!isset($return[$date])) {
                $statistic = [
                    'absence' => 0
                ];
                $return[$date] = $statistic;
            }
            if (!isset($return[$date]['absence'])) {
                $statistic = $return[$date];
                $statistic['absence'] = 0;
                $return[$date] = $statistic;
            }
            $return[$date]['absence'] += $entry->getDateTimeRange()->getInterval()->h;
            $return[$date]['absence'] += ($entry->getDateTimeRange()->getInterval()->i) / 60;
        }

        return new JsonResponse($return);
    }
}
