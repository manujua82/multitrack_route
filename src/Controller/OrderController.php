<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\CorrelativesRepository;
use App\Repository\OrderRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(
        Request $request,
        OrderRepository $repository,
        PaginatorInterface $paginatorInterface
    ): Response
    {
        $queryBuilder = $repository->searchByFilters();
        $pagination = $paginatorInterface->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );
        return $this->render('order/index.html.twig', [
            'orders' => $pagination,
        ]);
    }

    #[Route('/order/new', name: 'app_order_new', methods: ['GET', 'POST'])]
    public function add(
        Request $request,
        CorrelativesRepository $correlativesRepository,
        OrderRepository $orderRepository,
        TranslatorInterface $translator
    ): Response
    {
        $newOrder = new Order();
        $form = $this->createForm(OrderType::class, $newOrder, ['action' => $this->generateUrl('app_order_new')]);
        $orderCorrelative = $correlativesRepository->getByDocumentType("ORDER");
        $form->get('number')->setData($orderCorrelative->getNewNumber());
        $correlativesRepository->update($orderCorrelative, true);
        $form->get('date')->setData(new DateTime());
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $orderEntity = $form->getData();
            $orderRepository->add($orderEntity, true);

            $flashMessage = $translator->trans('Order number %code% was created', ['%code%' => $orderEntity->getNumber()]);
            $this->addFlash('success', $flashMessage);

            return $this->redirectToRoute('app_order');
        }

        return $this->render('order/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/order/{orderEntity}/edit', name: 'app_order_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Order $orderEntity,
        OrderRepository $orderRepository,
        TranslatorInterface $translator
    ): Response
    {
        //, ['action' => $this->generateUrl('app_order_edit', [ "orderEntity" => $orderEntity->getId() ])]);
        $form = $this->createForm(OrderType::class, $orderEntity, ['action' => $this->generateUrl('app_order_new')]);
        $form->get('date')->setData($orderEntity->getDate());

        if ($form->isSubmitted() && $form->isValid()) { 
            $orderEntity = $form->getData();
            $orderRepository->add($orderEntity, true);

            $flashMessage = $translator->trans('Order number %code% was Edited', ['%code%' => $orderEntity->getNumber()]);
            $this->addFlash('success', $flashMessage);

            return $this->redirectToRoute('app_order');
        }

        return $this->render('order/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/order/{orderEntity}/delete', name: 'app_order_delete')]
    public function delete(
        Order $orderEntity,
        OrderRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        $repository->delete($orderEntity, true);

        $flashMessage = $translator->trans('Order number %code% was deleted', ['%code%' => $orderEntity->getNumber()]);
        $this->addFlash('success', $flashMessage);

        return $this->redirectToRoute('app_order');
    }

}
