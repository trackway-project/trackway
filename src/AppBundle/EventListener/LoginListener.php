<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    /**
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        /** @var User $user */
        $user = $event->getAuthenticationToken()->getUser();
        if ($user && $user->getLocale()) {
            $event->getRequest()->getSession()->set('_locale', $user->getLocale()->getName());
        }
    }
}