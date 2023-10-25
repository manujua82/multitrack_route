<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationSetupController extends AbstractController
{
    #[Route('/notificationSetup', name: 'app_notification_setup')]
    public function index(): Response
    {
        return $this->render('notification_setup/index.html.twig', [
            'controller_name' => 'NotificationSetupController',
        ]);
    }
}
