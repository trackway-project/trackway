<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        if($this->container->get('security.context')->isGranted(array('IS_AUTHENTICATED_REMEMBERED'))) {
            $menu->addChild('Projects', array('route' => 'project'));
            $menu->addChild('Tasks', array('route' => 'task'));
            $menu->addChild('Teams', array('route' => 'team'));
            $menu->addChild('Time Entries', array('route' => 'timeentry'));
        }

        return $menu;
    }

    public function userMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');

        if($this->container->get('security.context')->isGranted(array('IS_AUTHENTICATED_REMEMBERED'))) {
            $username = $this->container->get('security.context')->getToken()->getUser()->getUsername();
            $menu->addChild($username, array('route' => 'fos_user_profile_show'));
            $menu->addChild('Logout', array('route' => 'fos_user_security_logout'));
        } else {
            $menu->addChild('Login', array('route' => 'fos_user_security_login'));
            $menu->addChild('Register', array('route' => 'fos_user_registration_register'));
        }

        return $menu;
    }
}