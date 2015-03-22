<?php

namespace AppBundle\Controller\User;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class ProfileController
 *
 * @package AppBundle\Controller
 *
 * @Route("/profile")
 */
class ProfileController extends Controller
{
    /**
     * @return array
     *
     * @Method("GET")
     * @Route("/", name="profile_show")
     * @Template()
     */
    public function showAction()
    {
        return ['entity' => $this->getUser()];
    }

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/edit", name="profile_edit")
     * @Template()
     */
    public function editAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this
            ->get('app.form.factory.profile')
            ->createForm([
                'activeTeam' => ['choices' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Team')->findByUser($user)],
                'submit' => ['label' => 'profile.template.edit.submit']
            ])
            ->setData($user)
            ->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            // TODO: Maybe putting it in a event listener?
            if ($user->getLocale()) {
                $request->getSession()->set('_locale', $user->getLocale()->getName());
            }

            $this->get('session')->getFlashBag()->add('success', 'profile.flash.updated');

            return $this->redirect($this->generateUrl('profile_show'));
        }

        return ['entity' => $user, 'form' => $form->createView()];
    }

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @Method("GET|POST")
     * @Route("/change-password", name="profile_change_password")
     * @Template()
     */
    public function changePasswordAction(Request $request)
    {
        /** @var User */
        $user = $this->getUser();

        $form = $this->get('app.form.factory.change_password')->createForm(['submit' => ['label' => 'profile.template.changePassword.submit']])->setData($user)->handleRequest($request);

        if ($form->isValid()) {
            $user->setPassword($this->container->get('security.password_encoder')->encodePassword($user, $user->getPassword()));

            $this->getDoctrine()->getManager()->flush();

            // Login
            $token = $this->get('security.authentication.manager')->authenticate(new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles()));
            $this->get('session')->set('_security_main', serialize($token));
            $this->get('security.token_storage')->setToken($token);

            $this->get('session')->getFlashBag()->add('success', 'profile.flash.passwordChanged');

            return $this->redirect($this->generateUrl('profile_show'));
        }

        return ['form' => $form->createView()];
    }
}
