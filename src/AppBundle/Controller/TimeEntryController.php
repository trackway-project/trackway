<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Absence;
use AppBundle\Entity\DateTimeRange;
use AppBundle\Entity\Repository\TimeEntryRepository;
use AppBundle\Entity\TimeEntry;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class TimeEntryController
 *
 * @package AppBundle\Controller
 *
 * @Route("/timeentry")
 */
class TimeEntryController extends Controller
{
    /**
     * Lists all existing TimeEntry entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/", name="timeentry_index")
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     * @Template()
     */
    public function indexAction(Request $request)
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
     * Shows an existing TimeEntry entity.
     *
     * @param TimeEntry $timeEntry
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/{id}", requirements={"id": "\d+"}, name="timeentry_show")
     * @Security("is_granted('VIEW', timeEntry)")
     * @Template()
     */
    public function showAction(TimeEntry $timeEntry)
    {
        return ['entity' => $timeEntry];
    }

    /**
     * Creates a new TimeEntry entity.
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/new", name="timeentry_new")
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     * @Template("AppBundle:Dashboard:form.modal.html.twig")
     */
    public function newAction(Request $request)
    {
        $timeEntry = new TimeEntry();

        $startsAtTimestamp = $request->get('start');
        $startsAt = new \DateTime();
        if ($startsAtTimestamp != null) {
            $startsAt->setTimestamp($startsAtTimestamp);
        }
        $endsAt = clone($startsAt);
        $endsAt->add(new \DateInterval('PT4H'));

        $dateTimeRange = new DateTimeRange();
        $dateTimeRange->setDate($startsAt);
        $dateTimeRange->setStartsAt($startsAt);
        $dateTimeRange->setEndsAt($endsAt);

        $timeEntry->setDateTimeRange($dateTimeRange);

        /** @var User $user */
        $user = $this->getUser();
        $activeTeam = $user->getActiveTeam();

        $form = $this->get('app.form.factory.time_entry')
            ->createForm(
                [
                    'project' => ['choices' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Project')->findByTeam($activeTeam)],
                    'task' => ['choices' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Task')->findByTeam($activeTeam)],
                    'action' => $this->generateUrl('timeentry_new'),
                    'submit' => ['label' => 'timeEntry.template.new.submit']
                ]
            )
            ->setData($timeEntry)
            ->handleRequest($request);

        if ($form->isValid()) {
            $timeEntry->setTeam($user->getActiveTeam());
            $timeEntry->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($timeEntry);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'timeEntry.flash.created');

            return new Response();
        }

        return ['form' => $form->createView()];
    }

    /**
     * Edits an existing TimeEntry entity.
     *
     * @param Request $request
     * @param TimeEntry $timeEntry
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="timeentry_edit")
     * @Security("is_granted('EDIT', timeEntry)")
     * @Template("AppBundle:Dashboard:form.modal.html.twig")
     */
    public function editAction(Request $request, TimeEntry $timeEntry)
    {
        $form =
            $this->get('app.form.factory.time_entry')
                ->createForm(['submit' => ['label' => 'timeEntry.template.edit.submit']])
                ->setData($timeEntry)
                ->handleRequest($request);

        if ($form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();

            $timeEntry->setTeam($user->getActiveTeam());
            $timeEntry->setUser($user);

            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'timeEntry.flash.updated');

            return new Response();
        }

        return [
            'deleteId' => $timeEntry->getId(),
            'type' => 'entry',
            'form' => $form->createView()
        ];
    }

    /**
     * Edits an existing TimeEntry entity.
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET")
     * @Route("/{id}/calendar_edit", requirements={"id": "\d+"}, name="timeentry_calendar_edit")
     * @Security("is_granted('EDIT', timeEntry)")
     */
    public function editCalendarAction(Request $request, TimeEntry $timeEntry)
    {
        $startTimestamp = $request->get('start');
        $start = new \DateTime();
        if ($startTimestamp != null) {
            $start->setTimestamp($startTimestamp);
        }
        $endTimestamp = $request->get('end');
        $end = new \DateTime();
        if ($endTimestamp != null) {
            $end->setTimestamp($endTimestamp);
        }

        $dateTimeRange = new DateTimeRange();
        $dateTimeRange->setDate($start);
        $dateTimeRange->setStartsAt($start);
        $dateTimeRange->setEndsAt($end);

        $timeEntry->setDateTimeRange($dateTimeRange);

        $em = $this->getDoctrine()->getManager();
        $em->persist($timeEntry);
        $em->flush();

        $return = ['status' => 'success'];
        return new JsonResponse($return);
    }

    /**
     * Deletes a TimeEntry entity.
     *
     * @param TimeEntry $timeEntry
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET")
     * @Route("/{id}/delete", requirements={"id": "\d+"}, name="timeentry_delete")
     * @Security("is_granted('EDIT', timeEntry)")
     */
    public function deleteAction(TimeEntry $timeEntry)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($timeEntry);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'timeEntry.flash.deleted');

        $return = ['status' => 'success'];
        return new JsonResponse($return);
    }

    /**
     * Deletes a TimeEntry entity.
     *
     * @param Request $request
     * @param TimeEntry $timeEntry
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET")
     * @Route("/{id}/copy", requirements={"id": "\d+"}, name="timeentry_copy")
     * @Security("is_granted('EDIT', timeEntry)")
     */
    public function copyAction(Request $request, TimeEntry $timeEntry)
    {
        $return = ['status' => 'error'];
        $startTimestamp = $request->get('start', false);
        $endTimestamp = $request->get('end', false);

        if ($startTimestamp !== false && $endTimestamp !== false) {
            $start = new \DateTime();
            if ($startTimestamp != null) {
                $start->setTimestamp($startTimestamp);
            }
            $newTimeEntry = new TimeEntry();

            $dateTimeRange = new DateTimeRange();
            $dateTimeRange->setDate($start);
            $dateTimeRange->setStartsAt($start);
            $end = new \DateTime();
            $end->setTimestamp($endTimestamp);
            $dateTimeRange->setEndsAt($end);
            $newTimeEntry->setDateTimeRange($dateTimeRange);

            $newTimeEntry->setNote($timeEntry->getNote());
            $newTimeEntry->setProject($timeEntry->getProject());
            $newTimeEntry->setTask($timeEntry->getTask());
            $newTimeEntry->setTeam($timeEntry->getTeam());
            $newTimeEntry->setUser($timeEntry->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($newTimeEntry);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'timeEntry.flash.copied');
            $return = ['status' => 'success'];
        }

        return new JsonResponse($return);
    }

    /**
     * @Method("GET")
     * @Route("/calendar", name="timeentry_calendar")
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     */
    public function calendarAction(Request $request)
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

    /**
     * @Method("GET")
     * @Route("/statistics", name="timeentry_statistics")
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
