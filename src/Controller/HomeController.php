<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Route as EntityRoute;
use App\Form\RouteType;
use App\Repository\CorrelativesRepository;
use App\Repository\OrderRepository;
use App\Repository\RouteRepository;
use DateInterval;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class HomeController extends AbstractController
{
    
    public function __construct(
        private CorrelativesRepository $correlativesRepository,
        private RouteRepository $routeRepository,
        private OrderRepository $orderRepository,
    )
    {
    }
    
    private function getRouteCorrelative(): string
    {
        $correlativeObj = $this->correlativesRepository->getByDocumentType("ROUTE");
        $newRouteCorrelative = $correlativeObj->getNewNumber();
        $this->correlativesRepository->update($correlativeObj, true);
        return $newRouteCorrelative;
    }

    private function getStartTime(): DateTime
    {
        $time = new DateTime();
        $time->setTime(8,0,0);
        return  $time;
    }

    private function getListOfRoutes()
    {
        $from = new DateTime();
        $from->sub(new DateInterval('P7D'));
        
        $till = new DateTime();
        $till->add(new DateInterval('P1D'));

        return $this->routeRepository->findByDateRange($from, $till);
    }
    

    #[Route('/', name: 'app_index')]
    public function index(Request $request): Response
    {
        return $this->render('home/index.html.twig', [
            'routes' => $this->getListOfRoutes(),
            'unscheduleOrders' => $this->orderRepository->getOrdersByStatus('Unschedule')
        ]);
    }

    #[Route('/routelist', name: 'app_route_list',  methods: ['GET', 'POST'])]
    public function routeList(Request $request): Response
    {
        return $this->render('home/_routeList.html.twig', [
            'routes' => $this->getListOfRoutes()
        ]);
    }

    #[Route('/routenew', name: 'app_route_new', methods: ['GET', 'POST'])]
    public function routeNew(Request $request, RouteRepository $repository): Response
    {
        $route = new EntityRoute();
        $route->setNumber($this->getRouteCorrelative());
        
        $form = $this->createForm(RouteType::class, $route);
        $form->get('date')->setData(new DateTime());
        $form->get('time')->setData($this->getStartTime());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $routeEntity = $form->getData();
            $routeEntity->setDate($form->get('date')->getData());
            $routeEntity->setTime($form->get('time')->getData());

            $repository->add($routeEntity, true);
            return new Response(null, 204);
        }

        return $this->render('home/_routeForm.html.twig', [
            'form' => $form->createView()
        ], new Response(
            null,
            $form->isSubmitted() && !$form->isValid() ? 422 : 200,
        ));  
    }

    #[Route('/route/{routeEntity}/edit', name: 'app_route_edit', methods: ['GET', 'POST'])]
    public function routeEdit(EntityRoute $routeEntity, Request $request, RouteRepository $repository): Response
    { 
        $form = $this->createForm(RouteType::class, $routeEntity);
        $form->get('date')->setData($routeEntity->getDate());
        $form->get('time')->setData($routeEntity->getTime());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $routeEntity = $form->getData();
            $routeEntity->setDate($form->get('date')->getData());
            $routeEntity->setTime($form->get('time')->getData());

            $repository->add($routeEntity, true);
            return new Response(null, 204);
        }
        
        return $this->render('home/_routeForm.html.twig', [
            'form' => $form->createView()
        ], new Response(
            null,
            $form->isSubmitted() && !$form->isValid() ? 422 : 200,
        ));  
    }

    #[Route('/route/{routeEntity}/delete', name: 'app_route_delete')]
    public function routeDelete(EntityRoute $routeEntity, RouteRepository $repository): Response
    {
        $repository->delete($routeEntity, true);
        return $this->redirectToRoute('app_index');
    }
}
