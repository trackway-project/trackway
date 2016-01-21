<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Absence;
use AppBundle\Entity\DateTimeRange;
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

/**
 * Class AbsenceController
 *
 * @package AppBundle\Controller
 *
 * @Route("/absence")
 */
class AbsenceController extends Controller
{
    /**
     * Creates a new Absence entity.
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/new", name="absence_create")
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     * @Template("AppBundle::modal.formcontent.html.twig")
     */
    public function newAction(Request $request)
    {
        $absence = new Absence();

        $startsAtTimestamp = $request->get('start');
        $startsAt = new \DateTime();
        if ($startsAtTimestamp != null) {
            $startsAt->setTimestamp($startsAtTimestamp);
        }

        $dateTimeRange = new DateTimeRange();
        $dateTimeRange->setDate($startsAt);
        $startsAt->setTime(9, 0, 0);
        $dateTimeRange->setStartsAt($startsAt);
        $endsAt = clone($startsAt);
        $endsAt->setTime(17, 0, 0);
        $dateTimeRange->setEndsAt($endsAt);

        $absence->setDateTimeRange($dateTimeRange);

        $form = $this->get('app.form.factory.absence')
            ->createForm([
                    'reason' => [
                        'choices' => $this->getDoctrine()->getManager()->getRepository('AppBundle:AbsenceReason')->findAll()
                    ],
                    'submit' => [
                        'label' => 'absence.template.new.submit'
                    ]
                ]
            )
            ->add('submitNew', SubmitType::class, [
                'label' => 'timeEntry.template.new.submitAndNew', 'attr' => ['value' => 1]
            ])
            ->setData($absence)
            ->handleRequest($request);

        if ($form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $absence->setTeam($user->getActiveTeam());
            $absence->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($absence);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'absence.flash.created');

            // Handle submitNew
            $formValues = $request->get('appbundle_absence_form', []);
            if (array_key_exists('submitNew', $formValues) && $formValues['submitNew']) {
                return $this->redirect($this->get('router')->generate('absence_create', [
                    'start' => $absence->getDateTimeRange()->getEndDateTime()->getTimestamp()
                ]));
            }

            return new Response();
        }

        return ['form' => $form->createView()];
    }

    /**
     * Edits an existing Absence entity.
     *
     * @param Request $request
     * @param Absence $absence
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="absence_edit", options={"expose"=true})
     * @Security("is_granted('EDIT', absence)")
     * @Template("AppBundle::modal.formcontent.html.twig")
     */
    public function editAction(Request $request, Absence $absence)
    {
        $form =
            $this->get('app.form.factory.absence')->createForm(['reason' => ['choices' => $this->getDoctrine()
                ->getManager()
                ->getRepository('AppBundle:AbsenceReason')
                ->findAll()],
                'submit' => ['label' => 'absence.template.edit.submit']])->setData($absence)->handleRequest($request);

        if ($form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();

            $absence->setTeam($user->getActiveTeam());
            $absence->setUser($user);

            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'absence.flash.updated');

            return new Response();
        }

        return [
            'deleteId' => $absence->getId(),
            'type' => 'absence',
            'form' => $form->createView()
        ];
    }

    /**
     * Deletes a Absence entity.
     *
     * @param Absence $absence
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET")
     * @Route("/{id}/delete", requirements={"id": "\d+"}, name="absence_delete", options={"expose"=true})
     * @Security("is_granted('EDIT', absence)")
     */
    public function deleteAction(Absence $absence)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($absence);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'absence.flash.deleted');

        $return = ['status' => 'success'];
        return new JsonResponse($return);
    }

    /**
     * Deletes a TimeEntry entity.
     *
     * @param Request $request
     * @param Absence $absence
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET")
     * @Route("/{id}/copy", requirements={"id": "\d+"}, name="absence_calendar_copy", options={"expose"=true})
     * @Security("is_granted('EDIT', absence)")
     */
    public function copyCalendarAction(Request $request, Absence $absence)
    {
        $return = ['status' => 'error'];

        $start = \DateTime::createFromFormat('Y-m-d?H:i:s', $request->get('start'));
        $end = \DateTime::createFromFormat('Y-m-d?H:i:s', $request->get('end'));

        if ($start !== false && $end !== false) {
            $dateTimeRange = new DateTimeRange();
            $dateTimeRange->setDate($start);
            $dateTimeRange->setStartsAt($start);
            $dateTimeRange->setEndsAt($end);

            $newAbsence = new Absence();
            $newAbsence->setDateTimeRange($dateTimeRange);
            $newAbsence->setNote($absence->getNote());
            $newAbsence->setReason($absence->getReason());
            $newAbsence->setTeam($absence->getTeam());
            $newAbsence->setUser($absence->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($newAbsence);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'absence.flash.copied');
            $return = ['status' => 'success'];
        }

        return new JsonResponse($return);
    }

    /**
     * Edits an existing Absence entity.
     *
     * @param Request $request
     * @param Absence $absence
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/{id}/calendar_edit", requirements={"id": "\d+"}, name="absence_calendar_edit", options={"expose"=true})
     * @Security("is_granted('EDIT', absence)")
     */
    public function editCalendarAction(Request $request, Absence $absence)
    {
        $return = ['status' => 'error'];

        $start = \DateTime::createFromFormat('Y-m-d?H:i:s', $request->get('start'));
        $end = \DateTime::createFromFormat('Y-m-d?H:i:s', $request->get('end'));

        if ($start !== false && $end !== false) {

            $dateTimeRange = new DateTimeRange();
            $dateTimeRange->setDate($start);
            $dateTimeRange->setStartsAt($start);
            $dateTimeRange->setEndsAt($end);

            $absence->setDateTimeRange($dateTimeRange);

            $em = $this->getDoctrine()->getManager();
            $em->persist($absence);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'absence.flash.moved');
            $return = ['status' => 'success'];
        }

        return new JsonResponse($return);
    }
}
