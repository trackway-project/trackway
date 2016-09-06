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
     * @return array
     *
     * @Method("GET")
     * @Route("/timeentry", name="timeentry_report")
     * @Security("is_granted('EDIT', user.getActiveTeam())")
     * @Template()
     */
    public function timeentryAction()
    {
        return [];
    }

    /**
     * @param Request $request
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/timeentry.{_format}", requirements={"_format"="csv|xls"}, name="timeentry_report_download", options={"expose"=true})
     * @Security("is_granted('EDIT', user.getActiveTeam())")
     * @Template("@App/Reports/timeentryDownload.twig")
     */
    public function timeentryDownloadAction(Request $request)
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
            'entries' => $this->getDoctrine()->getManager()->getRepository('AppBundle:TimeEntry')->findByTeam(
                $user->getActiveTeam(),
                $startDate,
                $endDate)
        ];
    }

    /**
     * @return array
     *
     * @Method("GET")
     * @Route("/absence", name="absence_report")
     * @Security("is_granted('EDIT', user.getActiveTeam())")
     * @Template()
     */
    public function absenceAction()
    {
        return [];
    }

    /**
     * @param Request $request
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/absence.{_format}", requirements={"_format"="csv|xls"}, name="absence_report_download", options={"expose"=true})
     * @Security("is_granted('EDIT', user.getActiveTeam())")
     * @Template("@App/Reports/absenceDownload.twig")
     */
    public function absenceDownloadAction(Request $request)
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
            'entries' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Absence')->findByTeam(
                $user->getActiveTeam(),
                $startDate,
                $endDate)
        ];
    }

    /**
     * @Method("GET")
     * @Route("/statistics", name="statistics", options={"expose"=true})
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
