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
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class RegistrationController
 *
 * @package AppBundle\Controller
 */
class RegistrationController extends Controller
{
    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/register", name="registration_register")
     * @Template()
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $user->setEnabled(false);

        $form = $this
            ->get('app.form.factory.registration')
            ->createForm([
                'submit' => ['label' => 'Register']
            ])
            ->setData($user)
            ->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // TODO: send mail

            $this->get('session')->getFlashBag()->add('success', 'registration.flash.registered');

            return $this->renderView('@App/User/Registration/checkMail.html.twig', ['entity' => $user]);
        }

        return ['entity' => $user, 'form' => $form->createView()];
    }

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @Method("GET")
     * @Route("/register/{token}", requirements={"token": "[a-zA-Z0-9]+"}, name="registration_confirm")
     */
    public function confirmAction(Request $request, $token)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findUserByConfirmationToken($token);

        if ($user === null) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        $user->setConfirmationToken(null);
        $user->setRegistrationRequestedAt(null);
        $user->setEnabled(true);

        $this->getDoctrine()->getManager()->flush();

        // Login
        $this->get('security.user_checker')->checkPostAuth($user);
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.authentication.session_strategy')->onAuthentication($this->container->get('request'), $token);
        $this->get('security.token_storage')->setToken($token);

        $this->get('session')->getFlashBag()->add('success', 'registration.flash.confirmed');

        return $this->redirect($this->generateUrl('dashboard_index'));
    }
}
