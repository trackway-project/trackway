<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class ProfileController
 *
 * @package AppBundle\Controller
 */
class ProfileController extends BaseController
{
    /**
     * @param Request $request
     *
     * @return null|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        /** @var User */
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this
            ->get('fos_user.profile.form.factory')
            ->createForm([
                'activeTeam' => ['choices' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Team')->findByUser($user)],
                'submit' => ['label' => 'profile.edit.submit', 'translation_domain' => 'FOSUserBundle']
            ])
            ->remove('memberships')
            ->setData($user)
            ->handleRequest($request);

        if ($form->isValid()) {
            dump(123);
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_profile_show');
                $response = new RedirectResponse($url);
            }

            // TODO: Maybe putting it in a event listener?
            if (!empty($user->getLocale())) {
                $request->getSession()->set('_locale', $user->getLocale());
            }

            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
