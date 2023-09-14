<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\RouteRepository;
use DateInterval;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class HomeController extends AbstractController
{
   
    #[Route('/', name: 'app_index')]
    public function index(RouteRepository $repository): Response
    {
       
        $from = new DateTime();
        $from->sub(new DateInterval('P7D'));
        
        $till = new DateTime();
        $till->add(new DateInterval('P1D'));
        
        return $this->render('home/index.html.twig', [
            'routes' => $repository->findByDateRange($from, $till)
        ]);
    }
}
