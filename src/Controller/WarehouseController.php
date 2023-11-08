<?php

namespace App\Controller;

use App\Entity\Warehouse;
use App\Form\WarehouseType;
use App\Repository\WarehouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class WarehouseController extends AbstractController
{
    #[Route('/warehouse', name: 'app_warehouse')]
    #[IsGranted('ROLE_VIEW_DIRECTORIES')]
    public function index(WarehouseRepository $warehouseRepository): Response
    {
        return $this->render('warehouse/index.html.twig', [
            'warehouses' => $warehouseRepository->findAllByCompany(),
        ]);
    }

    #[Route('/warehouse/new', name: 'app_warehouse_new')]
    #[IsGranted('ROLE_EDIT_DIRECTORIES')]
    public function add(
        Request $request, 
        WarehouseRepository $warehouseRepository
    ): Response
    {
        $form = $this->createForm(WarehouseType::class, new Warehouse());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $warehouseEntity = $form->getData();
            $warehouseRepository->add($warehouseEntity, true);
            return $this->redirectToRoute('app_warehouse');
        }
        return $this->render('warehouse/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/warehouse/{warehouseEntity}/edit', name: 'app_warehouse_edit')]
    #[IsGranted('ROLE_EDIT_DIRECTORIES')]
    public function edit(Warehouse $warehouseEntity, Request $request, WarehouseRepository $warehouseRepository): Response
    {
        $form = $this->createForm(WarehouseType::class, $warehouseEntity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $warehouseEntity = $form->getData();
            $warehouseRepository->add($warehouseEntity, true);
            return $this->redirectToRoute('app_warehouse');
        }

        return $this->render('warehouse/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/warehouse/{warehouseEntity}/delete', name: 'app_warehouse_delete')]
    #[IsGranted('ROLE_EDIT_DIRECTORIES')]
    public function delete(Warehouse $warehouseEntity, Request $request, WarehouseRepository $warehouseRepository): Response
    {
        $warehouseRepository->delete($warehouseEntity, true);
        return $this->redirectToRoute('app_driver');
    }

}
