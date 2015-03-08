<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
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
            $menu['Teams']->addChild('Overview', ['icon' => 'fa fa-fw fa-list', 'route' => 'team_index']);
            $menu['Teams']->addChild('Create', ['icon' => 'fa fa-fw fa-plus', 'route' => 'team_new']);
            if ($id && strpos($route, 'team_') === 0) {
                $menu['Teams']->addChild('Show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'team_show', 'routeParameters' => ['id' => $id]]);
                $menu['Teams']->addChild('Memberships', ['icon' => 'fa fa-fw fa-users', 'route' => 'team_membership_index', 'routeParameters' => ['id' => $id]]);
                $menu['Teams']->addChild('Edit', ['icon' => 'fa fa-fw fa-pencil-square-o', 'route' => 'team_edit', 'routeParameters' => ['id' => $id]]);
                $menu['Teams']->addChild('Delete', ['icon' => 'fa fa-fw fa-times', 'route' => 'team_delete', 'routeParameters' => ['id' => $id]]);

                $membershipId = $request->get('membershipId');
                if ($membershipId && $route !== 'team_membership_index' && strpos($route, 'team_membership_') === 0) {
                    $menu['Teams']['Memberships']->addChild('Back', ['icon' => 'fa fa-fw fa-arrow-circle-left', 'route' => 'team_membership_index', 'routeParameters' => ['id' => $id]]);
                    $menu['Teams']['Memberships']->addChild('Show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'team_membership_show', 'routeParameters' => ['id' => $id, 'membershipId' => $membershipId]]);
                }
            }

            $activeTeam = $this->container->get('security.token_storage')->getToken()->getUser()->getActiveTeam();

            if ($activeTeam) {
                $isTeamAdmin = $authorizationChecker->isGranted('EDIT', $activeTeam);

                $menu->addChild('Projects', ['route' => 'project_index']);
                $menu['Projects']->addChild('Overview', ['icon' => 'fa fa-fw fa-list', 'route' => 'project_index']);
                if ($isTeamAdmin) {
                    $menu['Projects']->addChild('Create', ['icon' => 'fa fa-fw fa-plus', 'route' => 'project_new']);
                }
                if ($id && strpos($route, 'project_') === 0) {
                    $menu['Projects']->addChild('Show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'project_show', 'routeParameters' => ['id' => $id]]);
                    if ($isTeamAdmin) {
                        $menu['Projects']->addChild('Edit', ['icon' => 'fa fa-fw fa-pencil-square-o', 'route' => 'project_edit', 'routeParameters' => ['id' => $id]]);
                        $menu['Projects']->addChild('Delete', ['icon' => 'fa fa-fw fa-times', 'route' => 'project_delete', 'routeParameters' => ['id' => $id]]);
                    }
                }

                $menu->addChild('Tasks', ['route' => 'task_index']);
                $menu['Tasks']->addChild('Overview', ['icon' => 'fa fa-fw fa-list', 'route' => 'task_index']);
                if ($isTeamAdmin) {
                    $menu['Tasks']->addChild('Create', ['icon' => 'fa fa-fw fa-plus', 'route' => 'task_new']);
                }
                if ($id && strpos($route, 'task_') === 0) {
                    $menu['Tasks']->addChild('Show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'task_show', 'routeParameters' => ['id' => $id]]);
                    if ($isTeamAdmin) {
                        $menu['Tasks']->addChild('Edit', ['icon' => 'fa fa-fw fa-pencil-square-o', 'route' => 'task_edit', 'routeParameters' => ['id' => $id]]);
                        $menu['Tasks']->addChild('Delete', ['icon' => 'fa fa-fw fa-times', 'route' => 'task_delete', 'routeParameters' => ['id' => $id]]);
                    }
                }

                $menu->addChild('Time Entries', ['route' => 'timeentry_index']);
                $menu['Time Entries']->addChild('Overview', ['icon' => 'fa fa-fw fa-list', 'route' => 'timeentry_index']);
                $menu['Time Entries']->addChild('Create', ['icon' => 'fa fa-fw fa-plus', 'route' => 'timeentry_new']);
                if ($id && strpos($route, 'timeentry_') === 0) {
                    $menu['Time Entries']->addChild('Show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'timeentry_show', 'routeParameters' => ['id' => $id]]);
                    $menu['Time Entries']->addChild('Edit', ['icon' => 'fa fa-fw fa-pencil-square-o', 'route' => 'timeentry_edit', 'routeParameters' => ['id' => $id]]);
                    $menu['Time Entries']->addChild('Delete', ['icon' => 'fa fa-fw fa-times', 'route' => 'timeentry_delete', 'routeParameters' => ['id' => $id]]);
                }

                $menu->addChild('Absences', ['route' => 'absence_index']);
                $menu['Absences']->addChild('Overview', ['icon' => 'fa fa-fw fa-list', 'route' => 'absence_index']);
                $menu['Absences']->addChild('Create', ['icon' => 'fa fa-fw fa-plus', 'route' => 'absence_new']);
                if ($id && strpos($route, 'absence_') === 0) {
                    $menu['Absences']->addChild('Show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'absence_show', 'routeParameters' => ['id' => $id]]);
                    $menu['Absences']->addChild('Edit', ['icon' => 'fa fa-fw fa-pencil-square-o', 'route' => 'absence_edit', 'routeParameters' => ['id' => $id]]);
                    $menu['Absences']->addChild('Delete', ['icon' => 'fa fa-fw fa-times', 'route' => 'absence_delete', 'routeParameters' => ['id' => $id]]);
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
            $menu['Profile']->addChild('Memberships', ['icon' => 'fa fa-fw fa-users', 'route' => 'profile_membership_index']);
            $menu['Profile']->addChild('Settings', ['route' => 'fos_user_profile_edit']);

            $menu->addChild('Logout', ['route' => 'fos_user_security_logout']);

            /** @var Request $request */
            $request = $this->container->get('request');
            $route = $request->get('_route');
            $id = $request->get('id');
            if ($id && strpos($route, 'profile_membership_') === 0) {
                $menu['Profile']['Memberships']->addChild('Back', ['icon' => 'fa fa-fw fa-arrow-circle-left', 'route' => 'profile_membership_index']);
                $menu['Profile']['Memberships']->addChild('Show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'profile_membership_show', 'routeParameters' => ['id' => $id]]);
            }
        } else {
            $menu->addChild('Login', ['route' => 'fos_user_security_login']);
            $menu->addChild('Register', ['route' => 'fos_user_registration_register']);
        }

        return $menu;
    }
}