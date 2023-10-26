<?php

namespace App\Controller;

use App\Form\CompanyType;
use App\Repository\MainCompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    #[Route('/company', name: 'app_company')]
    public function index(
        Request $request, 
        MainCompanyRepository $repository
    ): Response
    {
        $form = $this->createForm(CompanyType::class, $this->getUser()->getMainCompany());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $companyEntity = $form->getData();
            $repository->add($companyEntity, true);  

            return $this->redirectToRoute('app_company');
        }
        return $this->render('company/company.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
