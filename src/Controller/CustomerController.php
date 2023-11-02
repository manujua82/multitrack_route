<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CustomerController extends AbstractController
{
    #[Route('/customer', name: 'app_customer')]
    public function index(
        Request $request,
        CustomerRepository $repository, 
        PaginatorInterface $paginatorInterface): Response
    {

        $parent = $request->query->get('query', "");
        $pagination = $paginatorInterface->paginate(
            $repository->searchByQuery($parent), 
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );

        if ($request->query->get('preview') &&  !$request->query->get('page')) {
            return $this->render('customer/list.html.twig', [
                'customers' => $pagination,
            ]);
        }
        
        return $this->render('customer/index.html.twig', [
            'customers' => $pagination,
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
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $customerEntity = $form->getData();
            $repository->add($customerEntity, true);

            $flashMessage = $translator->trans('Customer %code% was created', ['%code%' => $customerEntity->getCode()]);
            $this->addFlash('success', $flashMessage);

            return $this->redirectToRoute('app_customer');
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
            $repository->add($customerEntity, true);
            
            return $this->redirectToRoute('app_customer');
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
        $repository->delete($customerEntity, true);

        $flashMessage = $translator->trans('Customer %code% was deleted', ['%code%' => $customerEntity->getCode()]);
        $this->addFlash('success', $flashMessage);
        
        return $this->redirectToRoute('app_customer');
    }
}
