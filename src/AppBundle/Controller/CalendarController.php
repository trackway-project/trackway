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
 * Class CalendarController
 *
 * @package AppBundle\Controller
 */
class CalendarController extends Controller
{
    /**
     * @return array
     *
     * @Method("GET")
     * @Route("/", name="calendar_index")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Method("GET")
     * @Route("/calendar", name="calendar_events")
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     */
    public function calendarEventsAction(Request $request)
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
            $return[] = [
                'id' => 'entry_' . $entry->getId(),
                'title' => $entry->getNote(),
                'start' => $entry->getDateTimeRange()->getStartDateTime()->getTimestamp() * 1000,
                'end' => $entry->getDateTimeRange()->getEndDateTime()->getTimestamp() * 1000,
                'allDay' => false,
                'className' => 'entry'
            ];
        }

        /** @var Absence $entry */
        foreach ($absenceResult as $entry) {
            $return[] = [
                'id' => 'absence_' . $entry->getId(),
                'title' => $entry->getNote(),
                'start' => $entry->getDateTimeRange()->getStartDateTime()->getTimestamp() * 1000,
                'end' => $entry->getDateTimeRange()->getEndDateTime()->getTimestamp() * 1000,
                'allDay' => false,
                'className' => 'absence'
            ];
        }

        return new JsonResponse($return);
    }

}
