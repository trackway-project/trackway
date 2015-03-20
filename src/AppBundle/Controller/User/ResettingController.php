<?php

namespace AppBundle\Controller\User;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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

        $form = $this->get('app.form.factory.resetting_request')->createForm(['submit' => ['label' => 'Reset']])->setData($user)->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findOneByEmail($user->getEmail());

            $user->setPasswordRequestedAt(new \DateTime());
            $user->setConfirmationToken(md5(uniqid(mt_rand(), true)));

            $this->getDoctrine()->getManager()->flush();

            // Send mail
            $mailer = $this->get('mailer');
            $message = $mailer->createMessage()->setSubject('You have Completed Registration!')->setFrom('no-reply@trackway.org')->setTo($user->getEmail())->setBody($this->renderView('@App/User/Resetting/email.html.twig', ['entity' => $user]), 'text/html');
            $mailer->send($message);

            $this->get('session')->getFlashBag()->add('success', 'resetting.flash.resetted');

            return $this->render('@App/User/Resetting/checkMail.html.twig', ['entity' => $user]);
        }

        return ['entity' => $user, 'form' => $form->createView()];
    }

    /**
     * @param Request $request
     * @param $token
     *
     * @return array|RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/resetting/{token}", requirements={"token": "[a-zA-Z0-9]+"}, name="resetting_confirm")
     * @Template()
     */
    public function confirmAction(Request $request, $token)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findOneByConfirmationToken($token);

        if ($user === null) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        $user->setPasswordRequestedAt(null);
        $user->setConfirmationToken(null);

        $form = $this->get('app.form.factory.resetting_confirm')->createForm(['submit' => ['label' => 'Reset']])->setData($user)->handleRequest($request);

        if ($form->isValid()) {
            $user->setPassword($this->container->get('security.password_encoder')->encodePassword($user, $user->getPassword()));

            $this->getDoctrine()->getManager()->flush();

            // Login
            $token = $this->get('security.authentication.manager')->authenticate(new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles()));
            $this->get('security.token_storage')->setToken($token);

            $this->get('session')->getFlashBag()->add('success', 'resetting.flash.confirmed');

            return $this->redirect($this->generateUrl('dashboard_index'));
        }

        return ['entity' => $user, 'form' => $form->createView()];
    }
}
