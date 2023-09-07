<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\CorrelativesRepository;
use App\Repository\OrderRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(OrderRepository $repository): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $repository->findAllByCompany(),
        ]);
    }

    #[Route('/order/new', name: 'app_order_new')]
    public function add(
        Request $request,
        CorrelativesRepository $correlativesRepository,
        OrderRepository $orderRepository
    ): Response
    {
        $orderCorrelative = $correlativesRepository->getByDocumentType("ORDER");
        $newOrder = new Order();
        $newOrder->setNumber($orderCorrelative->getNewNumber());
        $newOrder->setCreated(new DateTime());    
        $correlativesRepository->update($orderCorrelative, true);

        $form = $this->createForm(OrderType::class, $newOrder);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            
        }

        return $this->render('order/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/order/{orderEntity}/edit', name: 'app_order_edit')]
    public function edit(
        Order $orderEntity,
        Request $request
    ): Response
    {
        return $this->render('order/_form.html.twig', [
            'order' => $orderEntity,
        ]);
    }

    #[Route('/order/{orderEntity}/delete', name: 'app_order_delete')]
    public function delete(
        Order $orderEntity,
        Request $request
    ): Response
    {
        return $this->redirectToRoute('app_order');
    }

}
