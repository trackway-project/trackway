<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Builder
 *
 * @package AppBundle\Menu
 */
class Builder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param RequestStack $requestStack
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface $tokenStorage
     *
     * @return mixed
     */
    public function createSidebarMenu(RequestStack $requestStack, AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage)
    {
        $menu = $this->factory->createItem('root');

        if ($authorizationChecker->isGranted('ROLE_USER')) {
            /** @var Request $request */
            $request = $requestStack->getCurrentRequest();
            $route = $request->get('_route', '');
            $id = $request->get('id', false);
            $activeTeam = $tokenStorage->getToken()->getUser()->getActiveTeam();

            $menu->addChild('main', [
                'listTemplate' => 'AppBundle:Menu/Sidebar:listHeader.html.twig']);

            if ($activeTeam !== null) {
                $isTeamAdmin = $authorizationChecker->isGranted('EDIT', $activeTeam);

                $menu['main']->addChild('project', [
                    'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'listTemplate' => 'AppBundle:Menu/Sidebar:listTreeview.html.twig',
                    'route' => 'project_index']);
                $menu['main']->addChild('task', [
                    'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'listTemplate' => 'AppBundle:Menu/Sidebar:listTreeview.html.twig',
                    'route' => 'task_index']);
                $menu['main']->addChild('timeEntry', [
                    'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'listTemplate' => 'AppBundle:Menu/Sidebar:listTreeview.html.twig',
                    'route' => 'timeentry_index']);
                $menu['main']->addChild('absence', [
                    'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'listTemplate' => 'AppBundle:Menu/Sidebar:listTreeview.html.twig',
                    'route' => 'absence_index']);

                $menu['main']['project']->addChild('project.index', [
                    'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'icon' => 'fa fa-fw fa-list',
                    'route' => 'project_index']);
                $menu['main']['task']->addChild('task.index', [
                    'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'icon' => 'fa fa-fw fa-list',
                    'route' => 'task_index']);
                $menu['main']['timeEntry']->addChild('timeEntry.index', [
                    'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'icon' => 'fa fa-fw fa-list',
                    'route' => 'timeentry_index']);
                $menu['main']['timeEntry']->addChild('timeEntry.new', [
                    'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'icon' => 'fa fa-fw fa-plus',
                    'route' => 'timeentry_new']);
                $menu['main']['absence']->addChild('absence.index', [
                    'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'icon' => 'fa fa-fw fa-list',
                    'route' => 'absence_index']);
                $menu['main']['absence']->addChild('absence.new', [
                    'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'icon' => 'fa fa-fw fa-plus',
                    'route' => 'absence_new']);
                /*$menu['main']['team']->addChild('team.index', [
                    'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'icon' => 'fa fa-fw fa-list',
                    'route' => 'team_index']);
                $menu['main']['team']->addChild('team.new', [
                    'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'icon' => 'fa fa-fw fa-plus',
                    'route' => 'team_new']);*/

                if ($isTeamAdmin) {
                    $menu['main']['project']->addChild('project.new', [
                        'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-plus',
                        'route' => 'project_new']);
                    $menu['main']['task']->addChild('task.new', [
                        'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-plus',
                        'route' => 'task_new']);
                }

                /*if ($id && strpos($route, 'project_') === 0) {
                    $menu['main']['project']->addChild('project.show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'project_show', 'routeParameters' => ['id' => $id]]);
                    if ($isTeamAdmin) {
                        $menu['main']['project']->addChild('project.edit',
                            ['icon' => 'fa fa-fw fa-pencil-square-o', 'route' => 'project_edit', 'routeParameters' => ['id' => $id]]);
                        $menu['main']['project']->addChild('project.delete',
                            ['icon' => 'fa fa-fw fa-times', 'route' => 'project_delete', 'routeParameters' => ['id' => $id]]);
                    }
                }

                if ($id && strpos($route, 'task_') === 0) {
                    $menu['main']['task']->addChild('task.show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'task_show', 'routeParameters' => ['id' => $id]]);
                    if ($isTeamAdmin) {
                        $menu['main']['task']->addChild('task.edit',
                            ['icon' => 'fa fa-fw fa-pencil-square-o', 'route' => 'task_edit', 'routeParameters' => ['id' => $id]]);
                        $menu['main']['task']->addChild('task.delete', ['icon' => 'fa fa-fw fa-times', 'route' => 'task_delete', 'routeParameters' => ['id' => $id]]);
                    }
                }

                if ($id && strpos($route, 'timeentry_') === 0) {
                    $menu['main']['timeEntry']->addChild('timeEntry.show',
                        ['icon' => 'fa fa-fw fa-eye', 'route' => 'timeentry_show', 'routeParameters' => ['id' => $id]]);
                    $menu['main']['timeEntry']->addChild('timeEntry.edit',
                        ['icon' => 'fa fa-fw fa-pencil-square-o', 'route' => 'timeentry_edit', 'routeParameters' => ['id' => $id]]);
                    $menu['main']['timeEntry']->addChild('timeEntry.delete',
                        ['icon' => 'fa fa-fw fa-times', 'route' => 'timeentry_delete', 'routeParameters' => ['id' => $id]]);
                }

                if ($id && strpos($route, 'absence_') === 0) {
                    $menu['main']['absence']->addChild('absence.show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'absence_show', 'routeParameters' => ['id' => $id]]);
                    $menu['main']['absence']->addChild('absence.edit',
                        ['icon' => 'fa fa-fw fa-pencil-square-o', 'route' => 'absence_edit', 'routeParameters' => ['id' => $id]]);
                    $menu['main']['absence']->addChild('absence.delete',
                        ['icon' => 'fa fa-fw fa-times', 'route' => 'absence_delete', 'routeParameters' => ['id' => $id]]);
                }

                if ($id && strpos($route, 'team_') === 0) {
                    $menu['main']['team']->addChild('team.show', ['icon' => 'fa fa-fw fa-eye', 'route' => 'team_show', 'routeParameters' => ['id' => $id]]);
                    $menu['main']['team']->addChild('team.invite',
                        ['icon' => 'fa fa-fw fa-user-plus', 'route' => 'team_invitation_invite', 'routeParameters' => ['id' => $id]]);
                    $menu['main']['team']->addChild('team.invitation',
                        ['icon' => 'fa fa-fw fa-user-plus', 'route' => 'team_invitation_index', 'routeParameters' => ['id' => $id]]);
                    $menu['main']['team']->addChild('team.membership',
                        ['icon' => 'fa fa-fw fa-users', 'route' => 'team_membership_index', 'routeParameters' => ['id' => $id]]);
                    $menu['main']['team']->addChild('team.edit', ['icon' => 'fa fa-fw fa-pencil-square-o', 'route' => 'team_edit', 'routeParameters' => ['id' => $id]]);
                    $menu['main']['team']->addChild('team.delete', ['icon' => 'fa fa-fw fa-times', 'route' => 'team_delete', 'routeParameters' => ['id' => $id]]);

                    $membershipId = $request->get('membershipId');
                    if ($membershipId && $route !== 'team_membership_index' && strpos($route, 'team_membership_') === 0) {
                        $menu['main']['team']['team.membership']->addChild('team.membership.index',
                            ['icon' => 'fa fa-fw fa-arrow-circle-left', 'route' => 'team_membership_index', 'routeParameters' => ['id' => $id]]);
                        $menu['main']['team']['team.membership']->addChild('team.membership.show',
                            ['icon' => 'fa fa-fw fa-eye',
                                'route' => 'team_membership_show',
                                'routeParameters' => ['id' => $id, 'membershipId' => $membershipId]]);
                        $menu['main']['team']['team.membership']->addChild('team.membership.edit',
                            ['icon' => 'fa fa-fw fa-pencil-square-o',
                                'route' => 'team_membership_edit',
                                'routeParameters' => ['id' => $id, 'membershipId' => $membershipId]]);
                        $menu['main']['team']['team.membership']->addChild('team.membership.delete',
                            ['icon' => 'fa fa-fw fa-times',
                                'route' => 'team_membership_delete',
                                'routeParameters' => ['id' => $id, 'membershipId' => $membershipId]]);
                    }

                    $invitationId = $request->get('invitationId');
                    if ($invitationId && $route !== 'team_invitation_index' && strpos($route, 'team_invitation_') === 0) {
                        $menu['main']['team']['team.invitation']->addChild('team.invitation.index',
                            ['icon' => 'fa fa-fw fa-arrow-circle-left', 'route' => 'team_invitation_index', 'routeParameters' => ['id' => $id]]);
                        $menu['main']['team']['team.invitation']->addChild('team.invitation.show',
                            ['icon' => 'fa fa-fw fa-eye',
                                'route' => 'team_invitation_show',
                                'routeParameters' => ['id' => $id, 'invitationId' => $invitationId]]);
                        $menu['main']['team']['team.invitation']->addChild('team.invitation.delete',
                            ['icon' => 'fa fa-fw fa-times',
                                'route' => 'team_invitation_delete',
                                'routeParameters' => ['id' => $id, 'invitationId' => $invitationId]]);
                    }
                }*/
            }
        }

        if ($authorizationChecker->isGranted('ROLE_ADMIN')) {
            $menu->addChild('admin', [
                'listTemplate' => 'AppBundle:Menu/Sidebar:listHeader.html.twig']);

            $menu['admin']->addChild('admin.team', [
                'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                'listTemplate' => 'AppBundle:Menu/Sidebar:listTreeview.html.twig',
                'route' => 'admin_team_index']);
            $menu['admin']->addChild('admin.user', [
                'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                'listTemplate' => 'AppBundle:Menu/Sidebar:listTreeview.html.twig',
                'route' => 'admin_user_index']);
        }

        return $menu;
    }

    /**
     * @param RequestStack $requestStack
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface $tokenStorage
     *
     * @return mixed
     */
    public function createNavbarMenu(RequestStack $requestStack, AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage)
    {
        $menu = $this->factory->createItem('root');

        if ($authorizationChecker->isGranted('ROLE_USER')) {
            $username = $tokenStorage->getToken()->getUser()->getUsername();

            $menu->addChild('team', [
                'itemTemplate' => 'AppBundle:Menu/Navbar:itemTeam.html.twig',
                'listTemplate' => 'AppBundle:Menu/Navbar:listTeam.html.twig',
                'uri' => '#']);

            $menu['team']->addChild('team.switch', [
                'listTemplate' => 'AppBundle:Menu/Navbar:listTeamSwitch.html.twig']);
            $menu['team']->addChild('team.manage', [
                'itemTemplate' => 'AppBundle:Menu/Navbar:itemTeamFooter.html.twig',
                'route' => 'team_index']);

            $menu->addChild('user', [
                'itemTemplate' => 'AppBundle:Menu/Navbar:itemUser.html.twig',
                'listTemplate' => 'AppBundle:Menu/Navbar:listUser.html.twig',
                'label' => $username,
                'uri' => '#']);

            $menu['user']->addChild('user.footer', [
                'listTemplate' => 'AppBundle:Menu/Navbar:listUserFooter.html.twig']);

            $menu['user']['user.footer']->addChild('user.edit', [
                'itemTemplate' => 'AppBundle:Menu/Navbar:itemUserFooter.html.twig',
                'itemClass' => 'pull-left',
                'route' => 'profile_edit']);
            $menu['user']['user.footer']->addChild('user.logout', [
                'itemTemplate' => 'AppBundle:Menu/Navbar:itemUserFooter.html.twig',
                'itemClass' => 'pull-right',
                'route' => 'security_logout']);
        } else {
            $menu->addChild('login', [
                'itemTemplate' => 'AppBundle:Menu/Navbar:item.html.twig',
                'route' => 'security_login']);
            $menu->addChild('register', [
                'itemTemplate' => 'AppBundle:Menu/Navbar:item.html.twig',
                'route' => 'registration_register']);
        }

        return $menu;
    }
}
