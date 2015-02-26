<?php

namespace AppBundle\Menu;

use AppBundle\Entity\User;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        /** @var SecurityContext $securityContext */
        $securityContext = $this->container->get('security.context');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        if ($securityContext->isGranted(array('IS_AUTHENTICATED_REMEMBERED'))) {
            $menu->addChild('Teams', array('route' => 'team'));

            /** @var User $user */
            $user = $securityContext->getToken()->getUser();
            if ($user->getMemberships()->count() > 0) {
                $menu->addChild('Projects', array('route' => 'project'));
                $menu->addChild('Tasks', array('route' => 'task'));
                $menu->addChild('Time Entries', array('route' => 'timeentry'));
            }
        }

        return $menu;
    }

    public function userMenu(FactoryInterface $factory, array $options)
    {
        /** @var SecurityContext $securityContext */
        $securityContext = $this->container->get('security.context');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');

        if ($securityContext->isGranted(array('IS_AUTHENTICATED_REMEMBERED'))) {
            $username = $this->container->get('security.context')->getToken()->getUser()->getUsername();
            $menu->addChild($username, array('route' => 'fos_user_profile_edit'));
            $menu->addChild('Logout', array('route' => 'fos_user_security_logout'));
        } else {
            $menu->addChild('Login', array('route' => 'fos_user_security_login'));
            $menu->addChild('Register', array('route' => 'fos_user_registration_register'));
        }

        return $menu;
    }
}