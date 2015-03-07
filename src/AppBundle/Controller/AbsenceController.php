<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Absence;
use AppBundle\Entity\User;
use AppBundle\Form\Type\AbsenceFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Absence controller.
 *
 * @Route("/absence")
 */
class AbsenceController extends Controller
{
    /**
     * Lists all existing Absence entities.
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/", name="absence_index")
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     * @Template()
     */
    public function indexAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        return ['entities' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Absence')->findAllByTeamAndUser($user->getActiveTeam(), $user)];
    }

    /**
     * Shows an existing Absence entity.
     *
     * @param Absence $absence
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/{id}", requirements={"id": "\d+"}, name="absence_show")
     * @Security("is_granted('VIEW', absence)")
     * @Template()
     */
    public function showAction(Absence $absence)
    {
        return ['entity' => $absence];
    }

    /**
     * Creates a new Absence entity.
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/new", name="absence_new")
     * @Security("is_granted('VIEW', user.getActiveTeam())")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $absence = new Absence();
        $absence->setDate(new \DateTime());
        $absence->setStartsAt(new \DateTime());
        $absence->setEndsAt(new \DateTime());

        $form = $this->createForm(new AbsenceFormType(), $absence)->add('submit', 'submit', ['label' => 'Create'])->handleRequest($request);

        if ($form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $absence->setTeam($user->getActiveTeam());
            $absence->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($absence);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'absence.flash.created');

            return $this->redirect($this->generateUrl('absence_show', ['id' => $absence->getId()]));
        }

        return ['entity' => $absence, 'form' => $form->createView()];
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
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="absence_edit")
     * @Security("is_granted('EDIT', absence)")
     * @Template()
     */
    public function editAction(Request $request, Absence $absence)
    {
        $form = $this->createForm(new AbsenceFormType(), $absence)->add('submit', 'submit', ['label' => 'Update'])->handleRequest($request);

        if ($form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $absence->setTeam($user->getActiveTeam());
            $absence->setUser($user);
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'absence.flash.updated');

            return $this->redirect($this->generateUrl('absence_show', ['id' => $absence->getId()]));
        }

        return ['entity' => $absence, 'form' => $form->createView()];
    }

    /**
     * Deletes a Absence entity.
     *
     * @param Absence $absence
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Method("GET")
     * @Route("/{id}/delete", requirements={"id": "\d+"}, name="absence_delete")
     * @Security("is_granted('EDIT', absence)")
     */
    public function deleteAction(Absence $absence)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($absence);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'absence.flash.deleted');

        return $this->redirect($this->generateUrl('absence_index'));
    }
}
