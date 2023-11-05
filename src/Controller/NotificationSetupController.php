<?php

namespace App\Controller;

use App\Entity\NotificationSetup;
use App\Entity\OrderStatus;
use App\Form\NotificationSetupType;
use App\Repository\NotificationSetupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationSetupController extends AbstractController
{
    private $placeHolders;

    public function __construct()
    {
        $this->placeHolders = [
            "order_number",
            "order_alternative_number",
            "order_type",
            "order_address",
            "order_shipper",
            "order_note",
            "order_cdo",
            "order_time_from",
            "order_time_till",

            "driver_name",
            "driver_phone",

            "customer_name",
            
            "route_date",
            "eta_date",
            "eta_time"
        ];
    }

    private function renderOrderNotificationForm( $request, $repository, $status, $redirectRoute){
        $notification = $repository->getNotificationBy("ORDER", $status);
    
        $form = $this->createForm(NotificationSetupType::class, $notification);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $repository->add($entity, true);  
            return $this->redirectToRoute($redirectRoute);
        }

        return $this->render( 'notification_setup/_form.html.twig', [
            'form' => $form->createView(),
            'placeHolders' => $this->placeHolders
        ]);
    }
    #[Route('/notification/scheduled', name: 'app_notification_schedule')]
    public function index(
        Request $request,
        NotificationSetupRepository $repository
    ): Response
    {
        return $this->renderOrderNotificationForm(
            $request,
            $repository,
            OrderStatus::UNSCHEDULE->value,
            'app_notification_schedule'
        );
    }

    #[Route('/notification/route-start', name: 'app_notification_atRouteStart')]
    public function atRouteStart(
        Request $request,
        NotificationSetupRepository $repository
    ): Response
    {
        return $this->renderOrderNotificationForm(
            $request,
            $repository,
            OrderStatus::IN_PROGRESS->value,
            'app_notification_atRouteStart'
        );
    }

    #[Route('/notification/delivered', name: 'app_notification_atDelivered')]
    public function atDelivered(
        Request $request,
        NotificationSetupRepository $repository
    ): Response
    {
        return $this->renderOrderNotificationForm(
            $request,
            $repository,
            OrderStatus::DELIVERED->value,
            'app_notification_atDelivered'
        );
    }
}
