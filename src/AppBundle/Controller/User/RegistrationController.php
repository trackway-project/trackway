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

        $form = $this
            ->get('app.form.factory.registration')
            ->createForm([
                'submit' => ['label' => 'Register']
            ])
            ->setData($user)
            ->handleRequest($request);

        if ($form->isValid()) {
            $user->setConfirmationToken(md5(uniqid(mt_rand(), true)));
            $user->setEnabled(false);
            $user->setLocale($this->container->getParameter('locale'));
            $user->setRegistrationRequestedAt(new \DateTime());
            $user->setRoles(['ROLE_USER']);
            $user->setSalt(md5(uniqid(mt_rand(), true)));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // Send mail
            $mailer = $this->get('mailer');
            $message = $mailer->createMessage()
                ->setSubject('You have Completed Registration!')
                ->setFrom('no-reply@trackway.org')
                ->setTo($user->getEmail())
                ->setBody($this->renderView(
                        '@App/User/Registration/email.html.twig',
                        ['entity' => $user]
                    ), 'text/html');
            $mailer->send($message);

            $this->get('session')->getFlashBag()->add('success', 'registration.flash.registered');

            return $this->render('@App/User/Registration/checkMail.html.twig', ['entity' => $user]);
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
        $user = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findByConfirmationToken($token);

        if ($user === null) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        $user->setConfirmationToken(null);
        $user->setRegistrationRequestedAt(null);
        $user->setEnabled(true);

        $this->getDoctrine()->getManager()->flush();

        // Login
        $token = $this->get('security.authentication.manager')->authenticate(new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles()));
        $this->get('session')->set('_security_main', serialize($token));
        $this->get('security.token_storage')->setToken($token);

        $this->get('session')->getFlashBag()->add('success', 'registration.flash.confirmed');

        return $this->redirect($this->generateUrl('dashboard_index'));
    }
}
