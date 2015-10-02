<?php

namespace AppBundle\Menu;

use AppBundle\Entity\Repository\TeamRepository;
use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Builder
 *
 * @package AppBundle\Menu
 */
class Builder
{
    private static $notificationIcons = [
        'success' => 'fa fa-check-square-o text-green',
        'warning' => 'fa fa-exclamation text-yellow',
        'error' => 'fa fa-exclamation-triangle text-red'
    ];

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
                'template' => 'AppBundle:Menu/Sidebar:itemHeader.html.twig']);

            if ($activeTeam !== null) {
                $isTeamAdmin = $authorizationChecker->isGranted('EDIT', $activeTeam);

                // Create entity group
                $menu['main']->addChild('calendar', [
                    'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'route' => 'calendar_index']);
                $menu['main']->addChild('reports', [
                    'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'route' => 'timeentry_report']);
                $menu['main']->addChild('project', [
                    'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'route' => 'project_index']);
                $menu['main']->addChild('task', [
                    'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'route' => 'task_index']);

                // Create actions without context
                $menu['main']['project']->addChild('project.index', [
                    'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'icon' => 'fa fa-fw fa-list',
                    'route' => 'project_index']);
                $menu['main']['task']->addChild('task.index', [
                    'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'icon' => 'fa fa-fw fa-list',
                    'route' => 'task_index']);

                $menu['main']['reports']->addChild('reports.timeentry', [
                    'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'icon' => 'fa fa-fw fa-list',
                    'route' => 'timeentry_report']);
                $menu['main']['reports']->addChild('reports.absence', [
                    'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                    'icon' => 'fa fa-fw fa-list',
                    'route' => 'absence_report']);

                if ($isTeamAdmin) {
                    $menu['main']['project']->addChild('project.new', [
                        'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-plus',
                        'route' => 'project_new']);
                    $menu['main']['task']->addChild('task.new', [
                        'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-plus',
                        'route' => 'task_new']);
                }

                //
                // Action main group
                //

                // Project context
                if ($id && strpos($route, 'project_') === 0) {
                    $menu->addChild('action', [
                        'template' => 'AppBundle:Menu/Sidebar:itemHeader.html.twig']);
                    $menu['action']->addChild('project', [
                        'template' => 'AppBundle:Menu/Sidebar:itemInvisible.html.twig']);

                    $menu['action']['project']->addChild('project.show', [
                        'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-eye',
                        'route' => 'project_show',
                        'routeParameters' => ['id' => $id]]);
                    if ($isTeamAdmin) {
                        $menu['action']['project']->addChild('project.edit', [
                            'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-pencil-square-o',
                            'route' => 'project_edit',
                            'routeParameters' => ['id' => $id]]);
                        $menu['action']['project']->addChild('project.delete', [
                            'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-times',
                            'route' => 'project_delete',
                            'routeParameters' => ['id' => $id]]);
                    }
                }

                // Task context
                elseif ($id && strpos($route, 'task_') === 0) {
                    $menu->addChild('action', [
                        'template' => 'AppBundle:Menu/Sidebar:itemHeader.html.twig']);
                    $menu['action']->addChild('task', [
                        'template' => 'AppBundle:Menu/Sidebar:itemInvisible.html.twig']);

                    $menu['action']['task']->addChild('task.show', [
                        'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-eye',
                        'route' => 'task_show',
                        'routeParameters' => ['id' => $id]]);
                    if ($isTeamAdmin) {
                        $menu['action']['task']->addChild('task.edit', [
                            'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-pencil-square-o',
                            'route' => 'task_edit',
                            'routeParameters' => ['id' => $id]]);
                        $menu['action']['task']->addChild('task.delete', [
                            'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-times',
                            'route' => 'task_delete',
                            'routeParameters' => ['id' => $id]]);
                    }
                }

                // Time entry context
                elseif ($id && strpos($route, 'timeentry_') === 0) {
                    $menu->addChild('action', [
                        'template' => 'AppBundle:Menu/Sidebar:itemHeader.html.twig']);
                    $menu['action']->addChild('timeEntry', [
                        'template' => 'AppBundle:Menu/Sidebar:itemInvisible.html.twig']);

                    $menu['action']['timeEntry']->addChild('timeEntry.show', [
                        'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-eye',
                        'route' => 'timeentry_show',
                        'routeParameters' => ['id' => $id]]);
                    $menu['action']['timeEntry']->addChild('timeEntry.edit', [
                        'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-pencil-square-o',
                        'route' => 'timeentry_edit',
                        'routeParameters' => ['id' => $id]]);
                    $menu['action']['timeEntry']->addChild('timeEntry.delete', [
                        'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-times',
                        'route' => 'timeentry_delete',
                        'routeParameters' => ['id' => $id]]);
                }

                // Absence context
                elseif ($id && strpos($route, 'absence_') === 0) {
                    $menu->addChild('action', [
                        'template' => 'AppBundle:Menu/Sidebar:itemHeader.html.twig']);
                    $menu['action']->addChild('absence', [
                        'template' => 'AppBundle:Menu/Sidebar:itemInvisible.html.twig']);

                    $menu['action']['absence']->addChild('absence.show', [
                        'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-eye',
                        'route' => 'absence_show',
                        'routeParameters' => ['id' => $id]]);
                    $menu['action']['absence']->addChild('absence.edit', [
                        'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-pencil-square-o',
                        'route' => 'absence_edit',
                        'routeParameters' => ['id' => $id]]);
                    $menu['action']['absence']->addChild('absence.delete', [
                        'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                        'icon' => 'fa fa-fw fa-times',
                        'route' => 'absence_delete',
                        'routeParameters' => ['id' => $id]]);
                }

                // Team contexts
                elseif ($id && strpos($route, 'team_') === 0) {
                    $membershipId = $request->get('membershipId');
                    $invitationId = $request->get('invitationId');

                    $menu->addChild('action', [
                        'template' => 'AppBundle:Menu/Sidebar:itemHeader.html.twig']);
                    $menu['action']->addChild('team', [
                        'template' => 'AppBundle:Menu/Sidebar:itemInvisible.html.twig']);

                    // Membership context
                    if ($membershipId && strpos($route, 'team_membership_') === 0) {
                        $menu['action']['team']->addChild('membership', [
                            'template' => 'AppBundle:Menu/Sidebar:itemInvisible.html.twig']);

                        $menu['action']['team']['membership']->addChild('membership.show', [
                            'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-eye',
                            'route' => 'team_membership_show',
                            'routeParameters' => ['id' => $id, 'membershipId' => $membershipId]]);
                        $menu['action']['team']['membership']->addChild('membership.edit', [
                            'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-pencil-square-o',
                            'route' => 'team_membership_edit',
                            'routeParameters' => ['id' => $id, 'membershipId' => $membershipId]]);
                        $menu['action']['team']['membership']->addChild('membership.delete', [
                            'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-times',
                            'route' => 'team_membership_delete',
                            'routeParameters' => ['id' => $id, 'membershipId' => $membershipId]]);
                    }

                    // Invitation context
                    elseif ($invitationId && strpos($route, 'team_invitation_') === 0) {
                        $menu['action']['team']->addChild('invitation', [
                            'template' => 'AppBundle:Menu/Sidebar:itemInvisible.html.twig']);

                        $menu['action']['team']['invitation']->addChild('invitation.show', [
                            'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-eye',
                            'route' => 'team_invitation_show',
                            'routeParameters' => ['id' => $id, 'invitationId' => $invitationId]]);
                        $menu['action']['team']['invitation']->addChild('invitation.delete', [
                            'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-times',
                            'route' => 'team_invitation_delete',
                            'routeParameters' => ['id' => $id, 'invitationId' => $invitationId]]);
                    }

                    // Team context
                    else {
                        $menu['action']['team']->addChild('team.show', [
                            'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-eye',
                            'route' => 'team_show',
                            'routeParameters' => ['id' => $id]]);
                        $menu['action']['team']->addChild('team.invite', [
                            'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-envelope-o',
                            'route' => 'team_invitation_invite',
                            'routeParameters' => ['id' => $id]]);
                        $menu['action']['team']->addChild('team.invitation', [
                            'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-user-plus',
                            'route' => 'team_invitation_index',
                            'routeParameters' => ['id' => $id]]);
                        $menu['action']['team']->addChild('team.membership', [
                            'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-users',
                            'route' => 'team_membership_index',
                            'routeParameters' => ['id' => $id]]);
                        $menu['action']['team']->addChild('team.edit', [
                            'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-pencil-square-o',
                            'route' => 'team_edit',
                            'routeParameters' => ['id' => $id]]);
                        $menu['action']['team']->addChild('team.delete', [
                            'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-times',
                            'route' => 'team_delete',
                            'routeParameters' => ['id' => $id]]);
                    }
                }

                // Profile contexts
                elseif (strpos($route, 'profile_') === 0) {
                    $membershipId = $request->get('membershipId');

                    $menu->addChild('action', [
                        'template' => 'AppBundle:Menu/Sidebar:itemHeader.html.twig']);
                    $menu['action']->addChild('user', [
                        'template' => 'AppBundle:Menu/Sidebar:itemInvisible.html.twig']);

                    // Membership context
                    if ($membershipId && strpos($route, 'profile_membership_') === 0) {
                        $menu['action']['user']->addChild('membership',
                            ['template' => 'AppBundle:Menu/Sidebar:itemInvisible.html.twig']);

                        $menu['action']['user']['membership']->addChild('user.membership.show', [
                            'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-eye',
                            'route' => 'profile_membership_show',
                            'routeParameters' => ['membershipId' => $membershipId]]);
                        $menu['action']['user']['membership']->addChild('user.membership.delete', [
                            'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                            'icon' => 'fa fa-fw fa-times',
                            'route' => 'profile_membership_delete',
                            'routeParameters' => ['membershipId' => $membershipId]]);
                    }

                    // User context
                    else {
                        $menu['action']['user']->addChild('user.membership',
                            ['template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                                'icon' => 'fa fa-fw fa-users',
                                'route' => 'profile_membership_index']);
                        $menu['action']['user']->addChild('user.edit',
                            ['template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                                'icon' => 'fa fa-fw fa-pencil-square-o',
                                'route' => 'profile_edit']);
                        $menu['action']['user']->addChild('user.changePassword',
                            ['template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                                'icon' => 'fa fa-fw fa-key',
                                'route' => 'profile_change_password']);
                    }
                }
            } else {
                $menu->addChild('team.newRoot', ['route' => 'team_new']);
            }
        }

        //
        // Admin main group
        //

        if ($authorizationChecker->isGranted('ROLE_ADMIN')) {
            $menu->addChild('admin', [
                'template' => 'AppBundle:Menu/Sidebar:itemHeader.html.twig']);

            $menu['admin']->addChild('admin.team', [
                'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
                'route' => 'admin_team_index']);
            $menu['admin']->addChild('admin.user', [
                'template' => 'AppBundle:Menu/Sidebar:item.html.twig',
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

        // Create notification
        $menu->addChild('notification', [
            'template' => 'AppBundle:Menu/Navbar:itemNotification.html.twig',
            'uri' => '#']);

        if ($requestStack->getCurrentRequest()->hasSession()) {
            /**
             * @var Session $session
             */
            $session = $requestStack->getCurrentRequest()->getSession();

            // Create messages
            foreach($session->getFlashBag()->keys() as $key) {
                foreach($session->getFlashBag()->get($key) as $message) {
                    $menu['notification']->addChild($message, [
                        'template' => 'AppBundle:Menu/Navbar:itemNotificationMessage.html.twig',
                        'icon' => self::$notificationIcons[$key] . ' ' . $key,
                        'uri' => '#']);
                }
            }
        }

        if ($authorizationChecker->isGranted('ROLE_USER')) {
            /** @var User $user */
            $user = $tokenStorage->getToken()->getUser();
            $username = $user->getUsername();

            $menu->addChild('team', [
                'template' => 'AppBundle:Menu/Navbar:itemTeam.html.twig',
                'uri' => '#']);

            // Create group
            $menu['team']->addChild('team.switch', [
                'template' => 'AppBundle:Menu/Navbar:itemTeamSwitch.html.twig']);

            // Create actions
            $menu['team']->addChild('team.new', [
                'template' => 'AppBundle:Menu/Navbar:itemTeamFooter.html.twig',
                'route' => 'team_new']);
            $menu['team']->addChild('team.manage', [
                'template' => 'AppBundle:Menu/Navbar:itemTeamFooter.html.twig',
                'route' => 'team_index']);

            /** @var Team $team */
            foreach ($entityManager->getRepository('AppBundle:Team')->findByUser($user) as $team) {
                $menu['team']['team.switch']->addChild($team->getId(), [
                    'template' => 'AppBundle:Menu/Navbar:itemTeamSwitchEntry.html.twig',
                    'label' => $team->getName(),
                    'route' => 'team_activate',
                    'routeParameters' => ['id' => $team->getId()]]);
                if ($team === $tokenStorage->getToken()->getUser()->getActiveTeam()) {
                    $menu['team']['team.switch'][$team->getId()]->setExtra('icon', 'fa fa-star');
                }
            }

            $menu->addChild('user', [
                'template' => 'AppBundle:Menu/Navbar:itemUser.html.twig',
                'label' => $username,
                'uri' => '#']);

            $menu['user']->addChild('user.footer', [
                'template' => 'AppBundle:Menu/Navbar:itemUserFooter.html.twig']);

            $menu['user']['user.footer']->addChild('user.edit', [
                'template' => 'AppBundle:Menu/Navbar:itemUserFooterButton.html.twig',
                'itemClass' => 'pull-left',
                'route' => 'profile_edit']);
            $menu['user']['user.footer']->addChild('user.logout', [
                'template' => 'AppBundle:Menu/Navbar:itemUserFooterButton.html.twig',
                'itemClass' => 'pull-right',
                'route' => 'security_logout']);
        } else {
            $menu->addChild('login', [
                'template' => 'AppBundle:Menu/Navbar:item.html.twig',
                'route' => 'security_login']);
            $menu->addChild('register', [
                'template' => 'AppBundle:Menu/Navbar:item.html.twig',
                'route' => 'registration_register']);
        }

        return $menu;
    }
}
