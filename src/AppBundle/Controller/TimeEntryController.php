<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DateTimeRange;
use AppBundle\Entity\TimeEntry;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
     * @Template()
     */
    public function newAction(Request $request)
    {
        $timeEntry = new TimeEntry();

        $dateTimeRange = new DateTimeRange();
        $dateTimeRange->setDate(new \DateTime());
        $dateTimeRange->setStartsAt(new \DateTime());
        $dateTimeRange->setEndsAt(new \DateTime());

        $timeEntry->setDateTimeRange($dateTimeRange);

        /** @var User $user */
        $user = $this->getUser();
        $activeTeam = $user->getActiveTeam();

        $form =
            $this->get('app.form.factory.time_entry')
                ->createForm(['project' => ['choices' => $this->getDoctrine()
                    ->getManager()
                    ->getRepository('AppBundle:Project')
                    ->findByTeam($activeTeam)],
                    'task' => ['choices' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Task')->findByTeam($activeTeam)],
                    'submit' => ['label' => 'timeEntry.template.new.submit']])
                ->add('submitNew', 'submit', ['label' => 'timeEntry.template.new.submitAndNew', 'attr' => ['value' => 1]])
                ->setData($timeEntry)
                ->handleRequest($request);

        if ($form->isValid()) {
            $timeEntry->setTeam($user->getActiveTeam());
            $timeEntry->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($timeEntry);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'timeEntry.flash.created');

            // Handle submitNew
            $formValues = $request->get('appbundle_time_entry_form', []);
            if (array_key_exists('submitNew', $formValues) && $formValues['submitNew']) {
                return $this->redirect($this->generateUrl('timeentry_new'));
            }

            return $this->redirect($this->generateUrl('timeentry_show', ['id' => $timeEntry->getId()]));
        }

        return ['entity' => $timeEntry, 'form' => $form->createView()];
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
     * @Template()
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

            return $this->redirect($this->generateUrl('timeentry_show', ['id' => $timeEntry->getId()]));
        }

        return ['entity' => $timeEntry, 'form' => $form->createView()];
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

        return $this->redirect($this->generateUrl('timeentry_index'));
    }

    /**
     * @Method("GET")
     * @Route("/calendar", name="timeentry_calendar")
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     */
    public function calendarAction(Request $request){

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

        $return = [];

        /** @var TimeEntry $entry */
        foreach($timeEntryResult as $entry){
            $return[] = [
                'id' => $entry->getId(),
                'title' => $entry->getNote(),
                'start' => $entry->getDateTimeRange()->getStartDateTime()->format(\DateTime::ISO8601),
                'end' => $entry->getDateTimeRange()->getEndDateTime()->format(\DateTime::ISO8601),
                'allDay' => false
            ];
        }

        return new JsonResponse($return);

    }
}
