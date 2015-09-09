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
    private static $ROUTES = ['security_login', 'registration_register', 'registration_confirm', 'resetting_request ', 'resetting_confirm'];

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

        // Redirect the user to the calendar if authenticated and visiting a forbidden route

        if ($this->authorizationChecker->isGranted('ROLE_USER') && in_array($event->getRequest()->get('_route'), self::$ROUTES, false)) {
            $event->setResponse(RedirectResponse::create($this->router->generate('calendar_index')));
        }
    }
}