<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\Translator;

/**
 * Class NotificationController
 *
 * @package AppBundle\Controller
 *
 * @Route("/notification")
 */
class NotificationController extends Controller
{
    /**
     * Lists all notifications.
     *
     * @return array
     *
     * @Method("GET")
     * @Route("/", name="notification_index")
     */
    public function indexAction()
    {
        $notifications = [];

        if ($this->has('session')) {
            /**
             * @var Session $session
             */
            $session = $this->get('session');

            /**
             * @var Translator $translator
             */
            $translator = $this->get('translator');

            foreach($session->getFlashBag()->keys() as $key) {
                $notifications[$key] = [];
                foreach($session->getFlashBag()->get($key) as $message) {
                    $notifications[$key][] = $translator->trans($message, [], 'notifications');
                }
            }
        }

        return new JsonResponse($notifications);
    }
}