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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
     * Creates a new TimeEntry entity.
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/new", name="timeentry_create")
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     * @Template("AppBundle::modal.formcontent.html.twig")
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
        $endsAt->add(new \DateInterval('PT1H'));

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
                    'project' => [
                        'choices' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Project')->findByTeam($activeTeam)
                    ],
                    'action' => $this->generateUrl('timeentry_create'),
                    'submit' => [
                        'label' => 'timeEntry.template.new.submit'
                    ]
                ]
            )
            ->add('submitNew', SubmitType::class, [
                'label' => 'timeEntry.template.new.submitAndNew', 'attr' => ['value' => 1]
            ])
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
                return $this->redirect($this->get('router')->generate('timeentry_create', [
                    'start' => $timeEntry->getDateTimeRange()->getEndDateTime()->getTimestamp()
                ]));
            }

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
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="timeentry_edit", options={"expose"=true})
     * @Security("is_granted('EDIT', timeEntry)")
     * @Template("AppBundle::modal.formcontent.html.twig")
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
     * Deletes a TimeEntry entity.
     *
     * @param TimeEntry $timeEntry
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET")
     * @Route("/{id}/delete", requirements={"id": "\d+"}, name="timeentry_delete", options={"expose"=true})
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
     * @Route("/{id}/copy", requirements={"id": "\d+"}, name="timeentry_calendar_copy", options={"expose"=true})
     * @Security("is_granted('EDIT', timeEntry)")
     */
    public function copyCalendarAction(Request $request, TimeEntry $timeEntry)
    {
        $return = ['status' => 'error'];

        $start = \DateTime::createFromFormat('Y-m-d?H:i:s', $request->get('start'));
        $end = \DateTime::createFromFormat('Y-m-d?H:i:s', $request->get('end'));

        if ($start !== false && $end !== false) {
            $dateTimeRange = new DateTimeRange();
            $dateTimeRange->setDate($start);
            $dateTimeRange->setStartsAt($start);
            $dateTimeRange->setEndsAt($end);

            $newTimeEntry = new TimeEntry();
            $newTimeEntry->setDateTimeRange($dateTimeRange);
            $newTimeEntry->setNote($timeEntry->getNote());
            $newTimeEntry->setProject($timeEntry->getProject());
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
     * Edits an existing TimeEntry entity.
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET")
     * @Route("/{id}/calendar_edit", requirements={"id": "\d+"}, name="timeentry_calendar_edit", options={"expose"=true})
     * @Security("is_granted('EDIT', timeEntry)")
     */
    public function editCalendarAction(Request $request, TimeEntry $timeEntry)
    {
        $return = ['status' => 'error'];

        $start = \DateTime::createFromFormat('Y-m-d?H:i:s', $request->get('start'));
        $end = \DateTime::createFromFormat('Y-m-d?H:i:s', $request->get('end'));

        if ($start !== false && $end !== false) {

            $dateTimeRange = new DateTimeRange();
            $dateTimeRange->setDate($start);
            $dateTimeRange->setStartsAt($start);
            $dateTimeRange->setEndsAt($end);

            $timeEntry->setDateTimeRange($dateTimeRange);

            $em = $this->getDoctrine()->getManager();
            $em->persist($timeEntry);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'timeEntry.flash.moved');
            $return = ['status' => 'success'];
        }

        return new JsonResponse($return);
    }
}
