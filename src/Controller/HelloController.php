<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    private array $message = [
        "Hello", "Hi", "Baby"
    ];

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('hello/index.html.twig', ['message' => "Hello"]);
    }

    #[Route('/messages/{id}', name: 'app_showOne')]
    public function showOne($id): Response
    {
        return new Response($this->message[$id]);
    }
}
