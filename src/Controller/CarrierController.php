<?php

namespace App\Controller;

use App\Entity\Carrier;
use App\Form\CarrierType;
use App\Repository\CarrierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class CarrierController extends AbstractController
{
    #[Route('/carrier', name: 'app_carrier')]
    #[IsGranted('ROLE_VIEW_DIRECTORIES')]
    public function index(CarrierRepository $repository): Response
    {
        return $this->render('carrier/index.html.twig', [
            'carriers' => $repository->findAllByCompany(),
        ]);
    }

    #[Route('/carrier/new', name: 'app_carrier_new')]
    #[IsGranted('ROLE_EDIT_DIRECTORIES')]
    public function add(
        Request $request, 
        CarrierRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        $form = $this->createForm(CarrierType::class, new Carrier());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $carrierEntity = $form->getData();
            $repository->add($carrierEntity, true);   

            $flashMessage = $translator->trans('Carrier %code% was created', ['%code%' => $carrierEntity->getCode()]);
            $this->addFlash('success', $flashMessage);
            return $this->redirectToRoute('app_carrier');
        }

        return $this->render('carrier/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/carrier/{carrierEntity}/edit', name: 'app_carrier_edit')]
    #[IsGranted('ROLE_EDIT_DIRECTORIES')]
    public function edit(
        Carrier $carrierEntity, 
        Request $request, 
        CarrierRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        $form = $this->createForm(CarrierType::class, $carrierEntity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $carrierEntity = $form->getData();
            $repository->add($carrierEntity, true);

            $flashMessage = $translator->trans('Carrier %code% edit', ['%code%' => $carrierEntity->getCode()]);
            $this->addFlash('success', $flashMessage);

            return $this->redirectToRoute('app_carrier');
        }

        return $this->render('carrier/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/carrier/{carrierEntity}/delete', name: 'app_carrier_delete')]
    #[IsGranted('ROLE_EDIT_DIRECTORIES')]
    public function delete(
        Carrier $carrierEntity, 
        CarrierRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        $flashMessage = $translator->trans('Carrier %code% was deleted', ['%code%' => $carrierEntity->getCode()]);
        $this->addFlash('success', $flashMessage);

        $repository->delete($carrierEntity, true);
        return $this->redirectToRoute('app_carrier');
    }

}
