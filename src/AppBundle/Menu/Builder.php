<?php

namespace AppBundle\Menu;

use AppBundle\Entity\Repository\TeamRepository;
use AppBundle\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
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

            //
            // Entity main group
            //

            $menu->addChild('main', [
                'listTemplate' => 'AppBundle:Menu/Sidebar:listHeader.html.twig']);

            if ($activeTeam !== null) {
                $isTeamAdmin = $authorizationChecker->isGranted('EDIT', $activeTeam);

                // Create entity group
                $menu['main']->addChild('dashboard', [
                    'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'route' => 'dashboard_index']);
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

                // Create actions without context
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

                //
                // Action main group
                //

                // Project context
                if ($id && strpos($route, 'project_') === 0) {
                    $menu->addChild('action', [
                        'listTemplate' => 'AppBundle:Menu/Sidebar:listHeader.html.twig']);
                    $menu['action']->addChild('project', [
                        'listTemplate' => 'AppBundle:Menu/Sidebar:listInvisible.html.twig']);

                    $menu['action']['project']->addChild('project.show', [
                        'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-eye',
                        'route' => 'project_show',
                        'routeParameters' => ['id' => $id]]);
                    if ($isTeamAdmin) {
                        $menu['action']['project']->addChild('project.edit', [
                            'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-pencil-square-o',
                            'route' => 'project_edit',
                            'routeParameters' => ['id' => $id]]);
                        $menu['action']['project']->addChild('project.delete', [
                            'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-times',
                            'route' => 'project_delete',
                            'routeParameters' => ['id' => $id]]);
                    }
                }

                // Task context
                elseif ($id && strpos($route, 'task_') === 0) {
                    $menu->addChild('action', [
                        'listTemplate' => 'AppBundle:Menu/Sidebar:listHeader.html.twig']);
                    $menu['action']->addChild('task', [
                        'listTemplate' => 'AppBundle:Menu/Sidebar:listInvisible.html.twig']);

                    $menu['action']['task']->addChild('task.show', [
                        'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-eye',
                        'route' => 'task_show',
                        'routeParameters' => ['id' => $id]]);
                    if ($isTeamAdmin) {
                        $menu['action']['task']->addChild('task.edit', [
                            'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-pencil-square-o',
                            'route' => 'task_edit',
                            'routeParameters' => ['id' => $id]]);
                        $menu['action']['task']->addChild('task.delete', [
                            'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-times',
                            'route' => 'task_delete',
                            'routeParameters' => ['id' => $id]]);
                    }
                }

                // Time entry context
                elseif ($id && strpos($route, 'timeentry_') === 0) {
                    $menu->addChild('action', [
                        'listTemplate' => 'AppBundle:Menu/Sidebar:listHeader.html.twig']);
                    $menu['action']->addChild('timeEntry', [
                        'listTemplate' => 'AppBundle:Menu/Sidebar:listInvisible.html.twig']);

                    $menu['action']['timeEntry']->addChild('timeEntry.show', [
                        'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-eye',
                        'route' => 'timeentry_show',
                        'routeParameters' => ['id' => $id]]);
                    $menu['action']['timeEntry']->addChild('timeEntry.edit', [
                        'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-pencil-square-o',
                        'route' => 'timeentry_edit',
                        'routeParameters' => ['id' => $id]]);
                    $menu['action']['timeEntry']->addChild('timeEntry.delete', [
                        'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-times',
                        'route' => 'timeentry_delete',
                        'routeParameters' => ['id' => $id]]);
                }

                // Absence context
                elseif ($id && strpos($route, 'absence_') === 0) {
                    $menu->addChild('action', [
                        'listTemplate' => 'AppBundle:Menu/Sidebar:listHeader.html.twig']);
                    $menu['action']->addChild('absence', [
                        'listTemplate' => 'AppBundle:Menu/Sidebar:listInvisible.html.twig']);

                    $menu['action']['absence']->addChild('absence.show', [
                        'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-eye',
                        'route' => 'absence_show',
                        'routeParameters' => ['id' => $id]]);
                    $menu['action']['absence']->addChild('absence.edit', [
                        'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-pencil-square-o',
                        'route' => 'absence_edit',
                        'routeParameters' => ['id' => $id]]);
                    $menu['action']['absence']->addChild('absence.delete', [
                        'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-times',
                        'route' => 'absence_delete',
                        'routeParameters' => ['id' => $id]]);
                }

                // Team contexts
                elseif ($id && strpos($route, 'team_') === 0) {
                    $membershipId = $request->get('membershipId');
                    $invitationId = $request->get('invitationId');

                    $menu->addChild('action', [
                        'listTemplate' => 'AppBundle:Menu/Sidebar:listHeader.html.twig']);
                    $menu['action']->addChild('team', [
                        'listTemplate' => 'AppBundle:Menu/Sidebar:listInvisible.html.twig']);

                    // Membership context
                    if ($membershipId && strpos($route, 'team_membership_') === 0) {
                        $menu['action']['team']->addChild('membership', [
                            'listTemplate' => 'AppBundle:Menu/Sidebar:listInvisible.html.twig']);

                        $menu['action']['team']['membership']->addChild('membership.index', [
                            'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-arrow-circle-left',
                            'route' => 'team_membership_index',
                            'routeParameters' => ['id' => $id]]);
                        $menu['action']['team']['membership']->addChild('membership.show', [
                            'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-eye',
                            'route' => 'team_membership_show',
                            'routeParameters' => ['id' => $id, 'membershipId' => $membershipId]]);
                        $menu['action']['team']['membership']->addChild('membership.edit', [
                            'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-pencil-square-o',
                            'route' => 'team_membership_edit',
                            'routeParameters' => ['id' => $id, 'membershipId' => $membershipId]]);
                        $menu['action']['team']['membership']->addChild('membership.delete', [
                            'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-times',
                            'route' => 'team_membership_delete',
                            'routeParameters' => ['id' => $id, 'membershipId' => $membershipId]]);
                    }

                    // Invitation context
                    elseif ($invitationId && strpos($route, 'team_invitation_') === 0) {
                        $menu['action']['team']->addChild('invitation', [
                            'listTemplate' => 'AppBundle:Menu/Sidebar:listInvisible.html.twig']);

                        $menu['action']['team']['invitation']->addChild('invitation.index', [
                            'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-arrow-circle-left',
                            'route' => 'team_invitation_index',
                            'routeParameters' => ['id' => $id]]);
                        $menu['action']['team']['invitation']->addChild('invitation.show', [
                            'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-eye',
                            'route' => 'team_invitation_show',
                            'routeParameters' => ['id' => $id, 'invitationId' => $invitationId]]);
                        $menu['action']['team']['invitation']->addChild('invitation.delete', [
                            'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-times',
                            'route' => 'team_invitation_delete',
                            'routeParameters' => ['id' => $id, 'invitationId' => $invitationId]]);
                    }

                    // Team context
                    else {
                        $menu['action']['team']->addChild('team.show', [
                            'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-eye',
                            'route' => 'team_show',
                            'routeParameters' => ['id' => $id]]);
                        $menu['action']['team']->addChild('team.invite', [
                            'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-user-plus',
                            'route' => 'team_invitation_invite',
                            'routeParameters' => ['id' => $id]]);
                        $menu['action']['team']->addChild('team.invitation', [
                            'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-user-plus',
                            'route' => 'team_invitation_index',
                            'routeParameters' => ['id' => $id]]);
                        $menu['action']['team']->addChild('team.membership', [
                            'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-users',
                            'route' => 'team_membership_index',
                            'routeParameters' => ['id' => $id]]);
                        $menu['action']['team']->addChild('team.edit', [
                            'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-pencil-square-o',
                            'route' => 'team_edit',
                            'routeParameters' => ['id' => $id]]);
                        $menu['action']['team']->addChild('team.delete', [
                            'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-times',
                            'route' => 'team_delete',
                            'routeParameters' => ['id' => $id]]);
                    }
                }

                // Profile contexts
                elseif (strpos($route, 'profile_') === 0) {
                    $menu->addChild('action', [
                        'listTemplate' => 'AppBundle:Menu/Sidebar:listHeader.html.twig']);
                    $menu['action']->addChild('profile', [
                        'listTemplate' => 'AppBundle:Menu/Sidebar:listInvisible.html.twig']);

                    $menu['action']['profile']->addChild('profile.membership', [
                        'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-users',
                        'route' => 'profile_membership_index',
                        'routeParameters' => ['id' => $id]]);
                    $menu['action']['profile']->addChild('profile.edit', [
                        'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-pencil-square-o',
                        'route' => 'profile_edit']);
                    $menu['action']['profile']->addChild('profile.changePassword', [
                        'itemTemplate' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-key',
                        'route' => 'profile_change_password']);
                }
            }
        }

        //
        // Admin main group
        //

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
     * @param EntityManagerInterface $entityManager
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function createNavbarMenu(RequestStack $requestStack, AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager)
    {
        $menu = $this->factory->createItem('root');

        if ($authorizationChecker->isGranted('ROLE_USER')) {
            $username = $tokenStorage->getToken()->getUser()->getUsername();

            $menu->addChild('team', [
                'itemTemplate' => 'AppBundle:Menu/Navbar:itemTeam.html.twig',
                'listTemplate' => 'AppBundle:Menu/Navbar:listTeam.html.twig',
                'uri' => '#']);

            // Create group
            $menu['team']->addChild('team.switch', [
                'listTemplate' => 'AppBundle:Menu/Navbar:listTeamSwitch.html.twig']);

            // Create actions
            $menu['team']->addChild('team.new', [
                'itemTemplate' => 'AppBundle:Menu/Navbar:itemTeamFooter.html.twig',
                'route' => 'team_new']);
            $menu['team']->addChild('team.manage', [
                'itemTemplate' => 'AppBundle:Menu/Navbar:itemTeamFooter.html.twig',
                'route' => 'team_index']);

            /** @var Team $team */
            foreach ($entityManager->getRepository('AppBundle:Team')->findAll() as $team) {
                $menu['team']['team.switch']->addChild($team->getName(), [
                    'itemTemplate' => 'AppBundle:Menu/Navbar:itemTeamSwitch.html.twig',
                    'route' => 'team_activate',
                    'routeParameters' => ['id' => $team->getId()]]);
                if ($team === $tokenStorage->getToken()->getUser()->getActiveTeam()) {
                    $menu['team']['team.switch'][$team->getName()]->setExtra('icon', 'fa fa-star');
                }
            }

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
