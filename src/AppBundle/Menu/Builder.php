<?php

namespace AppBundle\Menu;

use AppBundle\Entity\User;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory)
    {
        /** @var SecurityContext $securityContext */
        $securityContext = $this->container->get('security.context');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        if ($securityContext->isGranted(['IS_AUTHENTICATED_REMEMBERED'])) {
            $menu->addChild('Teams', ['route' => 'team']);

            /** @var User $user */
            $user = $securityContext->getToken()->getUser();
            if ($user->getMemberships()->count() > 0) {
                $menu->addChild('Projects', ['route' => 'project']);
                $menu->addChild('Tasks', ['route' => 'task']);
                $menu->addChild('Time Entries', ['route' => 'timeentry']);
            }
        }

        return $menu;
    }

    public function userMenu(FactoryInterface $factory)
    {
        /** @var SecurityContext $securityContext */
        $securityContext = $this->container->get('security.context');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');

        if ($securityContext->isGranted(['IS_AUTHENTICATED_REMEMBERED'])) {
            $username = $securityContext->getToken()->getUser()->getUsername();
            $menu->addChild($username, ['route' => 'fos_user_profile_edit']);
            $menu->addChild('Logout', ['route' => 'fos_user_security_logout']);
        } else {
            $menu->addChild('Login', ['route' => 'fos_user_security_login']);
            $menu->addChild('Register', ['route' => 'fos_user_registration_register']);
        }

        return $menu;
    }
}