<?php

namespace AppBundle\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    /**
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        if ($user && !empty($user->getLocale())) {
            $event->getRequest()->getSession()->set('_locale', $user->getLocale());
        }
    }
}