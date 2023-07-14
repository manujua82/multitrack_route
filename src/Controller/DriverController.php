<?php

namespace App\Controller;

use App\Entity\Driver;
use App\Form\DriverType;
use App\Repository\DriverRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DriverController extends AbstractController
{
    #[Route('/driver', name: 'app_driver')]
    public function index(DriverRepository $driverRepository): Response
    {
        return $this->render('driver/index.html.twig', [
            'drivers' => $driverRepository->findAll(),
        ]);
    }

    #[Route('/driver/new', name: 'app_driver_add', priority:2)]
    public function add(Request $request, DriverRepository $driverRepository): Response
    {
        $form = $this->createForm(DriverType::class, new Driver());
        return $this->handleDriverFormRequest( $form, $driverRepository, $request);
    }

    #[Route('/driver/{driverEntity}/edit', name: 'app_driver_edit')]
    public function edit(Driver $driverEntity, Request $request, DriverRepository $driverRepository): Response
    {
        $form = $this->createForm(DriverType::class, $driverEntity);
        return $this->handleDriverFormRequest( $form, $driverRepository, $request, true);
    }

    private function handleDriverFormRequest($form, $repository, $request, $isEdit = false)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $driverEntity = $form->getData();
            $repository->add($driverEntity, true);

            if ($isEdit) {
                $this->addFlash('success', 'your micro post have been added');
            }else{
                $this->addFlash('success', 'your micro post have been updated');
            }
            // Redirect index
            return $this->redirectToRoute('app_driver');
        }

        return $this->render('driver/new.html.twig', [
            'form' => $form
        ]);

    }
}
