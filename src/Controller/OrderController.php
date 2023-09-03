<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        OrderRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        return $this->render('order/_form.html.twig', [
            'order' => new Order(),
        ]);
    }
}
