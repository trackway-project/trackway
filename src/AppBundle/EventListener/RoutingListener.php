<?php

namespace AppBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class RoutingListener
{
    /**
     * @var AuthorizationChecker
     */
    private $authorizationChecker;

    /**
     * @var Router
     */
    private $router;

    public function __construct(AuthorizationChecker $authorizationChecker, Router $router)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if ($this->authorizationChecker->isGranted('ROLE_USER') && in_array($event->getRequest()->get('_route'), ['fos_user_security_login', 'fos_user_registration_register', 'fos_user_registration_check_email', 'fos_user_registration_confirm', 'fos_user_registration_confirmed', 'fos_user_resetting_request ', 'fos_user_resetting_send_email', 'fos_user_resetting_check_email', 'fos_user_resetting_reset', 'fos_user_change_password'], false)
        ) {
            $event->setResponse(RedirectResponse::create($this->router->generate('dashboard_index')));
        }
    }
}