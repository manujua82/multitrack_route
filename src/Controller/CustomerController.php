<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CustomerController extends AbstractController
{
    #[Route('/customer', name: 'app_customer')]
    public function index(CustomerRepository $repository): Response
    {
        return $this->render('customer/index.html.twig', [
            'customers' => $repository->findAllByCompany(),
        ]);
    }

    #[Route('/customer/new', name: 'app_customer_new')]
    public function add(
        Request $request, 
        CustomerRepository $repository,
        TranslatorInterface $translator
    ): Response
    {

        $customer = new Customer();
        // $address = new Address();
        // $address->setCode("TestCode");

        // $customer->addAddress($address); 

        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $customerEntity = $form->getData();
            dd($customerEntity); 
        }

        return $this->render('customer/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/customer/{customerEntity}/edit', name: 'app_customer_edit')]
    public function edit(
        Customer $customerEntity,
        Request $request, 
        CustomerRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        $form = $this->createForm(CustomerType::class, $customerEntity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $customerEntity = $form->getData();
            dd($customerEntity); 
        }

        return $this->render('customer/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/customer/{customerEntity}/delete', name: 'app_customer_delete')]
    public function delete(
        Customer $customerEntity,
        CustomerRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        $flashMessage = $translator->trans('Customer %code% was deleted', ['%code%' => $customerEntity->getCode()]);
        $this->addFlash('success', $flashMessage);

        $repository->delete($customerEntity, true);
        return $this->redirectToRoute('app_customer');
    }
}
