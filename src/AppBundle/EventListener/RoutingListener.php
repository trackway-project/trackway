<?php

namespace AppBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class RoutingListener
{
    /**
     * These routes are forbidden if the user is authenticated
     *
     * @var array
     */
    private static $ROUTES = ['fos_user_security_login', 'fos_user_registration_register', 'fos_user_registration_check_email', 'fos_user_registration_confirm', 'fos_user_registration_confirmed', 'fos_user_resetting_request ', 'fos_user_resetting_send_email', 'fos_user_resetting_check_email', 'fos_user_resetting_reset', 'fos_user_change_password'];

    /**
     * @var AuthorizationChecker
     */
    private $authorizationChecker;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var Router
     */
    private $router;

    /**
     * @param AuthorizationChecker $authorizationChecker
     * @param TokenStorage $tokenStorage
     * @param Router $router
     */
    public function __construct(AuthorizationChecker $authorizationChecker, TokenStorage $tokenStorage, Router $router)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        // Do nothing if it is not the master request or user not authenticated

        if (!$event->isMasterRequest() || !$this->tokenStorage->getToken()) {
            return;
        }

        // Redirect the user to the dashboard if authenticated and visiting a forbidden route

        if ($this->authorizationChecker->isGranted('ROLE_USER') && in_array($event->getRequest()->get('_route'), self::$ROUTES, false)) {
            $event->setResponse(RedirectResponse::create($this->router->generate('dashboard_index')));
        }
    }
}