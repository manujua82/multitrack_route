<?php

namespace App\Controller;

use App\Repository\CarrierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UtilsController extends AbstractController
{
    #[Route('/utility/carriers', methods:'GET', name:'utilsCarriers')]
    public function getCarriersApi(CarrierRepository $carrierRepository, Request $request)
    {
        $carriers = $carrierRepository->findAllMatching($request->query->get('query'));
        return $this->json([
            'carriers' => $carriers
        ]);
    }
}