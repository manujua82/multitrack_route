<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Form\VehicleType;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VehicleController extends AbstractController
{
    #[Route('/vehicle', name: 'app_vehicle')]
    public function index(VehicleRepository $repository): Response
    {
        return $this->render('vehicle/index.html.twig', [
            'vehicles' => $repository->findAllByCompany(),
        ]);
    }

    #[Route('/vehicle/new', name: 'app_vehicle_new')]
    public function add(
        Request $request, 
        VehicleRepository $repository
    ): Response
    {
        $form = $this->createForm(VehicleType::class, new Vehicle() );
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $vehicleEntity = $form->getData();
            $repository->add($vehicleEntity, true);
            return $this->redirectToRoute('app_vehicle');
        }

        return $this->render('vehicle/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/vehicle/{vehicleEntity}/edit', name: 'app_vehicle_edit')]
    public function edit(Vehicle $vehicleEntity, Request $request, VehicleRepository $repository): Response
    {
        $form = $this->createForm(VehicleType::class, $vehicleEntity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $vehicleEntity = $form->getData();
            $repository->add($vehicleEntity, true);
            return $this->redirectToRoute('app_vehicle');
        }
         return $this->render('vehicle/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/vehicle/{vehicleEntity}/delete', name: 'app_vehicle_delete')]
    public function delete(Vehicle $vehicleEntity, Request $request, VehicleRepository $repository): Response
    {
        $repository->delete($vehicleEntity, true);
        return $this->redirectToRoute('app_vehicle');
    }
}
