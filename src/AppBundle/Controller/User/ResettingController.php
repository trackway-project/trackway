<?php

namespace AppBundle\Controller\User;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ResettingController
 *
 * @package AppBundle\Controller
 */
class ResettingController extends Controller
{
    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/resetting", name="resetting_request")
     * @Template()
     */
    public function requestAction(Request $request)
    {
        $user = new User();

        $form = $this
            ->get('app.form.factory.resetting_request')
            ->createForm([
                'submit' => ['label' => 'Register']
            ])
            ->setData($user)
            ->handleRequest($request);

        if ($form->isValid()) {

            // TODO: send mail

            $this->get('session')->getFlashBag()->add('success', 'resetting.flash.resetted');

            return $this->renderView('@App/User/Resetting/checkMail.html.twig', ['entity' => $user]);
        }

        return ['entity' => $user, 'form' => $form->createView()];
    }

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/resetting/{token}", requirements={"token": "[a-zA-Z0-9]+"}, name="resetting_confirm")
     * @Template()
     */
    public function confirmAction(Request $request, $token)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findUserByConfirmationToken($token);

        if ($user === null) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        $user->setPasswordRequestedAt(null);
        $user->setConfirmationToken(null);

        $form = $this
            ->get('app.form.factory.resetting_confirm')
            ->createForm([
                'submit' => ['label' => 'Reset']
            ])
            ->setData($user)
            ->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'resetting.flash.confirmed');

            return $this->redirect($this->generateUrl('security_login'));
        }

        return ['entity' => $user, 'form' => $form->createView()];
    }
}
