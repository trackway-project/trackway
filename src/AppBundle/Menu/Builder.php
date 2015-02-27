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
        $menu = $factory->createItem('root')
            ->setChildrenAttribute('class', 'nav navbar-nav');

        /** @var SecurityContext $securityContext */
        $securityContext = $this->container->get('security.context');
        if ($securityContext && $securityContext->isGranted(['IS_AUTHENTICATED_REMEMBERED'])) {
            $menu->addChild('Teams', ['route' => 'team_index']);

            /** @var User $user */
            $user = $securityContext->getToken()->getUser();
            if ($user->getMemberships()->count() > 0) {
                $menu->addChild('Projects', ['route' => 'project_index']);
                $menu->addChild('Tasks', ['route' => 'task_index']);
                $menu->addChild('Time Entries', ['route' => 'timeentry_index']);
            }
        }

        return $menu;
    }

    public function userMenu(FactoryInterface $factory)
    {
        $menu = $factory->createItem('root')
            ->setChildrenAttribute('class', 'nav navbar-nav navbar-right');

        /** @var SecurityContext $securityContext */
        $securityContext = $this->container->get('security.context');
        if ($securityContext && $securityContext->isGranted(['IS_AUTHENTICATED_REMEMBERED'])) {
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