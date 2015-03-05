<?php

namespace AppBundle\Menu;

use AppBundle\Entity\User;
use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory)
    {
        $menu = $factory->createItem('root');

        /** @var AuthorizationChecker $authorizationChecker */
        $authorizationChecker = $this->container->get('security.authorization_checker');

        if ($authorizationChecker->isGranted('ROLE_USER')) {
            /** @var Request $request */
            $request = $this->container->get('request');
            $route = $request->get('_route');
            $id = $request->get('id');

            $menu->addChild('Teams', ['route' => 'team_index']);
            $menu['Teams']->addChild('Overview', ['route' => 'team_index']);
            $menu['Teams']->addChild('Create', ['route' => 'team_new']);
            if (strpos($route, 'team_') === 0 && $id) {
                $menu['Teams']->addChild('Show', ['route' => 'team_show', 'routeParameters' => ['id' => $id]]);
                $menu['Teams']->addChild('Edit', ['route' => 'team_edit', 'routeParameters' => ['id' => $id]]);
                $menu['Teams']->addChild('Delete', ['route' => 'team_delete', 'routeParameters' => ['id' => $id]]);
            }

            $activeTeam = $this->container->get('security.token_storage')->getToken()->getUser()->getActiveTeam();

            if ($activeTeam) {
                $isTeamAdmin = $authorizationChecker->isGranted('EDIT', $activeTeam);

                $menu->addChild('Projects', ['route' => 'project_index']);
                $menu['Projects']->addChild('Overview', ['route' => 'project_index']);
                if ($isTeamAdmin) {
                    $menu['Projects']->addChild('Create', ['route' => 'project_new']);
                }
                if (strpos($route, 'project_') === 0 && $id) {
                    $menu['Projects']->addChild('Show', ['route' => 'project_show', 'routeParameters' => ['id' => $id]]);
                    if ($isTeamAdmin) {
                        $menu['Projects']->addChild('Edit', ['route' => 'project_edit', 'routeParameters' => ['id' => $id]]);
                        $menu['Projects']->addChild('Delete', ['route' => 'project_delete', 'routeParameters' => ['id' => $id]]);
                    }
                }

                $menu->addChild('Tasks', ['route' => 'task_index']);
                $menu['Tasks']->addChild('Overview', ['route' => 'task_index']);
                if ($isTeamAdmin) {
                    $menu['Tasks']->addChild('Create', ['route' => 'task_new']);
                }
                if (strpos($route, 'task_') === 0 && $id) {
                    $menu['Tasks']->addChild('Show', ['route' => 'task_show', 'routeParameters' => ['id' => $id]]);
                    if ($isTeamAdmin) {
                        $menu['Tasks']->addChild('Edit', ['route' => 'task_edit', 'routeParameters' => ['id' => $id]]);
                        $menu['Tasks']->addChild('Delete', ['route' => 'task_delete', 'routeParameters' => ['id' => $id]]);
                    }
                }

                $menu->addChild('Time Entries', ['route' => 'timeentry_index']);
                $menu['Time Entries']->addChild('Overview', ['route' => 'timeentry_index']);
                $menu['Time Entries']->addChild('Create', ['route' => 'timeentry_new']);
                if (strpos($route, 'timeentry_') === 0 && $id) {
                    $menu['Time Entries']->addChild('Show', ['route' => 'timeentry_show', 'routeParameters' => ['id' => $id]]);
                    $menu['Time Entries']->addChild('Edit', ['route' => 'timeentry_edit', 'routeParameters' => ['id' => $id]]);
                    $menu['Time Entries']->addChild('Delete', ['route' => 'timeentry_delete', 'routeParameters' => ['id' => $id]]);
                }

                $menu->addChild('Absences', ['route' => 'absence_index']);
                $menu['Absences']->addChild('Overview', ['route' => 'absence_index']);
                $menu['Absences']->addChild('Create', ['route' => 'absence_new']);
                if (strpos($route, 'absence_') === 0 && $id) {
                    $menu['Absences']->addChild('Show', ['route' => 'absence_show', 'routeParameters' => ['id' => $id]]);
                    $menu['Absences']->addChild('Edit', ['route' => 'absence_edit', 'routeParameters' => ['id' => $id]]);
                    $menu['Absences']->addChild('Delete', ['route' => 'absence_delete', 'routeParameters' => ['id' => $id]]);
                }
            }
        }

        return $menu;
    }

    public function userMenu(FactoryInterface $factory)
    {
        $menu = $factory->createItem('root');

        /** @var AuthorizationChecker $authorizationChecker */
        $authorizationChecker = $this->container->get('security.authorization_checker');

        if ($authorizationChecker->isGranted('ROLE_USER')) {
            $username = $this->container->get('security.token_storage')->getToken()->getUser()->getUsername();

            $menu->addChild('Profile', ['label' => $username, 'route' => 'fos_user_profile_show']);
            $menu['Profile']->addChild('Profile', ['route' => 'fos_user_profile_show']);
            $menu['Profile']->addChild('Settings', ['route' => 'fos_user_profile_edit']);

            $menu->addChild('Logout', ['route' => 'fos_user_security_logout']);
        } else {
            $menu->addChild('Login', ['route' => 'fos_user_security_login']);
            $menu->addChild('Register', ['route' => 'fos_user_registration_register']);
        }

        return $menu;
    }
}