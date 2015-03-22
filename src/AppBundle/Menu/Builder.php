<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * Class Builder
 *
 * @package AppBundle\Menu
 */
class Builder implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface|null
     */
    private $container;

    /**
     * @var AuthorizationChecker|null
     */
    private $authorizationChecker;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->authorizationChecker = $container !== null ? $container->get('security.authorization_checker') : null;
    }

    /**
     * @param FactoryInterface $factory
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function mainMenu(FactoryInterface $factory)
    {
        $menu = $factory->createItem('root');

        if ($this->authorizationChecker !== null && $this->authorizationChecker->isGranted('ROLE_USER')) {
            /** @var Request $request */
            $request = $this->container->get('request');
            $route = $request->get('_route');
            $id = $request->get('id');
            $activeTeam = $this->container->get('security.token_storage')->getToken()->getUser()->getActiveTeam();

            if ($activeTeam) {
                $isTeamAdmin = $this->authorizationChecker->isGranted('EDIT', $activeTeam);

                $menu->addChild('project', ['route' => 'project_index']);
                $menu['project']->addChild('project.index', ['icon' => 'fa fa-fw fa-list', 'route' => 'project_index']);
                if ($isTeamAdmin) {
                    $menu['project']->addChild('project.new', ['icon' => 'fa fa-fw fa-plus', 'route' => 'project_new']);
                }
                if ($id && strpos($route, 'project_') === 0) {
                    $menu['project']->addChild('project.show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'project_show', 'routeParameters' => ['id' => $id]]);
                    if ($isTeamAdmin) {
                        $menu['project']->addChild('project.edit', ['icon' => 'fa fa-fw fa-pencil-square-o', 'route' => 'project_edit', 'routeParameters' => ['id' => $id]]);
                        $menu['project']->addChild('project.delete', ['icon' => 'fa fa-fw fa-times', 'route' => 'project_delete', 'routeParameters' => ['id' => $id]]);
                    }
                }

                $menu->addChild('task', ['route' => 'task_index']);
                $menu['task']->addChild('task.index', ['icon' => 'fa fa-fw fa-list', 'route' => 'task_index']);
                if ($isTeamAdmin) {
                    $menu['task']->addChild('task.new', ['icon' => 'fa fa-fw fa-plus', 'route' => 'task_new']);
                }
                if ($id && strpos($route, 'task_') === 0) {
                    $menu['task']->addChild('task.show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'task_show', 'routeParameters' => ['id' => $id]]);
                    if ($isTeamAdmin) {
                        $menu['task']->addChild('task.edit', ['icon' => 'fa fa-fw fa-pencil-square-o', 'route' => 'task_edit', 'routeParameters' => ['id' => $id]]);
                        $menu['task']->addChild('task.delete', ['icon' => 'fa fa-fw fa-times', 'route' => 'task_delete', 'routeParameters' => ['id' => $id]]);
                    }
                }

                $menu->addChild('timeEntry', ['route' => 'timeentry_index']);
                $menu['timeEntry']->addChild('timeEntry.index', ['icon' => 'fa fa-fw fa-list', 'route' => 'timeentry_index']);
                $menu['timeEntry']->addChild('timeEntry.new', ['icon' => 'fa fa-fw fa-plus', 'route' => 'timeentry_new']);
                if ($id && strpos($route, 'timeentry_') === 0) {
                    $menu['timeEntry']->addChild('timeEntry.show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'timeentry_show', 'routeParameters' => ['id' => $id]]);
                    $menu['timeEntry']->addChild('timeEntry.edit', ['icon' => 'fa fa-fw fa-pencil-square-o', 'route' => 'timeentry_edit', 'routeParameters' => ['id' => $id]]);
                    $menu['timeEntry']->addChild('timeEntry.delete', ['icon' => 'fa fa-fw fa-times', 'route' => 'timeentry_delete', 'routeParameters' => ['id' => $id]]);
                }

                $menu->addChild('absence', ['route' => 'absence_index']);
                $menu['absence']->addChild('absence.index', ['icon' => 'fa fa-fw fa-list', 'route' => 'absence_index']);
                $menu['absence']->addChild('absence.new', ['icon' => 'fa fa-fw fa-plus', 'route' => 'absence_new']);
                if ($id && strpos($route, 'absence_') === 0) {
                    $menu['absence']->addChild('absence.show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'absence_show', 'routeParameters' => ['id' => $id]]);
                    $menu['absence']->addChild('absence.edit', ['icon' => 'fa fa-fw fa-pencil-square-o', 'route' => 'absence_edit', 'routeParameters' => ['id' => $id]]);
                    $menu['absence']->addChild('absence.delete', ['icon' => 'fa fa-fw fa-times', 'route' => 'absence_delete', 'routeParameters' => ['id' => $id]]);
                }
            }
        }

        return $menu;
    }

    /**
     * @param FactoryInterface $factory
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function teamMenu(FactoryInterface $factory)
    {
        $menu = $factory->createItem('root');

        if ($this->authorizationChecker !== null && $this->authorizationChecker->isGranted('ROLE_USER')) {
            /** @var Request $request */
            $request = $this->container->get('request');
            $route = $request->get('_route');
            $id = $request->get('id');

            $menu->addChild('team', ['route' => 'team_index']);
            $menu['team']->addChild('team.index', ['icon' => 'fa fa-fw fa-list', 'route' => 'team_index']);
            $menu['team']->addChild('team.new', ['icon' => 'fa fa-fw fa-plus', 'route' => 'team_new']);
            if ($id && strpos($route, 'team_') === 0) {
                $menu['team']->addChild('team.show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'team_show', 'routeParameters' => ['id' => $id]]);
                $menu['team']->addChild('team.invite', ['icon' => 'fa fa-fw fa-user-plus', 'route' => 'team_invitation_invite', 'routeParameters' => ['id' => $id]]);
                $menu['team']->addChild('team.invitation', ['icon' => 'fa fa-fw fa-user-plus', 'route' => 'team_invitation_index', 'routeParameters' => ['id' => $id]]);
                $menu['team']->addChild('team.membership', ['icon' => 'fa fa-fw fa-users', 'route' => 'team_membership_index', 'routeParameters' => ['id' => $id]]);
                $menu['team']->addChild('team.edit', ['icon' => 'fa fa-fw fa-pencil-square-o', 'route' => 'team_edit', 'routeParameters' => ['id' => $id]]);
                $menu['team']->addChild('team.delete', ['icon' => 'fa fa-fw fa-times', 'route' => 'team_delete', 'routeParameters' => ['id' => $id]]);

                $membershipId = $request->get('membershipId');
                if ($membershipId && $route !== 'team_membership_index' && strpos($route, 'team_membership_') === 0) {
                    $menu['team']['team.membership']->addChild('team.membership.back', ['icon' => 'fa fa-fw fa-arrow-circle-left', 'route' => 'team_membership_index', 'routeParameters' => ['id' => $id]]);
                    $menu['team']['team.membership']->addChild('team.membership.show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'team_membership_show', 'routeParameters' => ['id' => $id, 'membershipId' => $membershipId]]);
                    $menu['team']['team.membership']->addChild('team.membership.edit', ['icon' => 'fa fa-fw fa-pencil-square-o', 'route' => 'team_membership_edit', 'routeParameters' => ['id' => $id, 'membershipId' => $membershipId]]);
                    $menu['team']['team.membership']->addChild('team.membership.delete', ['icon' => 'fa fa-fw fa-times', 'route' => 'team_membership_delete', 'routeParameters' => ['id' => $id, 'membershipId' => $membershipId]]);
                }

                $invitationId = $request->get('invitationId');
                if ($invitationId && $route !== 'team_invitation_index' && strpos($route, 'team_invitation_') === 0) {
                    $menu['team']['team.invitation']->addChild('team.invitation.back', ['icon' => 'fa fa-fw fa-arrow-circle-left', 'route' => 'team_invitation_index', 'routeParameters' => ['id' => $id]]);
                    $menu['team']['team.invitation']->addChild('team.invitation.show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'team_invitation_show', 'routeParameters' => ['id' => $id, 'invitationId' => $invitationId]]);
                    $menu['team']['team.invitation']->addChild('team.invitation.delete', ['icon' => 'fa fa-fw fa-times', 'route' => 'team_invitation_delete', 'routeParameters' => ['id' => $id, 'invitationId' => $invitationId]]);
                }
            }
        }

        return $menu;
    }

    /**
     * @param FactoryInterface $factory
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function userMenu(FactoryInterface $factory)
    {
        $menu = $factory->createItem('root');

        if ($this->authorizationChecker !== null && $this->authorizationChecker->isGranted('ROLE_USER')) {
            $username = $this->container->get('security.token_storage')->getToken()->getUser()->getUsername();

            $menu->addChild('profile', ['label' => $username, 'route' => 'profile_show']);
            $menu['profile']->addChild('profile.show', ['icon' => 'fa fa-fw fa-user', 'route' => 'profile_show']);
            $menu['profile']->addChild('profile.membership', ['icon' => 'fa fa-fw fa-users', 'route' => 'profile_membership_index']);
            $menu['profile']->addChild('profile.edit', ['icon' => 'fa fa-fw fa-pencil-square-o', 'route' => 'profile_edit']);
            $menu['profile']->addChild('profile.changePassword', ['icon' => 'fa fa-fw fa-key', 'route' => 'profile_change_password']);
            $menu['profile']->addChild('profile.logout', ['icon' => 'fa fa-fw fa-sign-out', 'route' => 'security_logout']);

            /** @var Request $request */
            $request = $this->container->get('request');
            $route = $request->get('_route');
            $id = $request->get('id');
            if ($id && strpos($route, 'profile_membership_') === 0) {
                $menu['profile']['profile.membership']->addChild('profile.membership.back', ['icon' => 'fa fa-fw fa-arrow-circle-left', 'route' => 'profile_membership_index']);
                $menu['profile']['profile.membership']->addChild('profile.membership.show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'profile_membership_show', 'routeParameters' => ['id' => $id]]);
                $menu['profile']['profile.membership']->addChild('profile.membership.delete', ['icon' => 'fa fa-fw fa-times', 'route' => 'profile_membership_delete', 'routeParameters' => ['id' => $id]]);
            }
        } else {
            $menu->addChild('login', ['route' => 'security_login']);
            $menu->addChild('register', ['route' => 'registration_register']);
        }

        return $menu;
    }

    /**
     * @param FactoryInterface $factory
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function adminMenu(FactoryInterface $factory)
    {
        $menu = $factory->createItem('root');

        if ($this->authorizationChecker !== null && $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $menu->addChild('admin', ['route' => 'admin_team_index']);
            $menu['admin']->addChild('admin.team', ['route' => 'admin_team_index']);
            $menu['admin']->addChild('admin.user', ['route' => 'admin_user_index']);
        }

        return $menu;
    }
}