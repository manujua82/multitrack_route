<?php

namespace App\Controller;

use App\Entity\RoutingSetup;
use App\Form\RoutingSetupType;
use App\Repository\RoutingSetupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoutingSetupController extends AbstractController
{
    #[Route('/routingSetup', name: 'app_routing_setup')]
    public function index(
        Request $request,
        RoutingSetupRepository $repository
    ): Response
    {
        $routingSetup = $repository->getRoutingSetup();
        if ($routingSetup === null) {
            $routingSetup = new RoutingSetup();
        }

        $form = $this->createForm(RoutingSetupType::class, $routingSetup);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $setupEntity = $form->getData();
            dd( $setupEntity);
            $repository->add($setupEntity, true);  
            return $this->redirectToRoute('app_index');
        }
        return $this->render('routing_setup/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
