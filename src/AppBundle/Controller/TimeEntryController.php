<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TimeEntry;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @return array
     *
     * @Method("GET")
     * @Route("/", name="timeentry_index")
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     * @Template()
     */
    public function indexAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        return ['entities' => $this->getDoctrine()->getManager()->getRepository('AppBundle:TimeEntry')->findByTeamAndUser($user->getActiveTeam(), $user)];
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
        $timeEntry->setDate(new \DateTime());
        $timeEntry->setStartsAt(new \DateTime());
        $timeEntry->setEndsAt(new \DateTime());

        /** @var User $user */
        $user = $this->getUser();
        $activeTeam = $user->getActiveTeam();

        $form = $this
            ->get('app.form.factory.time_entry')
            ->createForm([
                'project' => ['choices' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Project')->findByTeam($activeTeam)],
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
        $form = $this->get('app.form.factory.time_entry')->createForm(['submit' => ['label' => 'timeEntry.template.edit.submit']])->setData($timeEntry)->handleRequest($request);

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
}
