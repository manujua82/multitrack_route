<?php

namespace App\Controller;

use App\Entity\Shipper;
use App\Form\ShipperType;
use App\Repository\ShipperRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ShipperController extends AbstractController
{   
    #[Route('/shipper', name: 'app_shipper')]
    #[IsGranted('ROLE_VIEW_DIRECTORIES')]
    public function index(ShipperRepository $repository): Response
    {
        return $this->render('shipper/index.html.twig', [
            'shippers' => $repository->findAllByCompany(),
        ]);
    }

    #[Route('/shipper/new', name: 'app_shipper_new')]
    #[IsGranted('ROLE_EDIT_DIRECTORIES')]
    public function add(
        Request $request, 
        ShipperRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        $form = $this->createForm(ShipperType::class, new Shipper());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $newShipper = $form->getData();
            $repository->add($newShipper, true);   

            $flashMessage = $translator->trans('Shipper created flash', ['code' => $newShipper->getCode()]);
            $this->addFlash('success', $flashMessage);
            return $this->redirectToRoute('app_shipper');
        }

        return $this->render('shipper/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/shipper/{shipperEntity}/edit', name: 'app_shipper_edit')]
    #[IsGranted('ROLE_EDIT_DIRECTORIES')]
    public function edit(
        Shipper $shipperEntity, 
        Request $request, 
        ShipperRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        $form = $this->createForm(ShipperType::class, $shipperEntity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $newShipper = $form->getData();
            $repository->add($newShipper, true);   

            $flashMessage = $translator->trans('Shipper edit flash', ['code' => $newShipper->getCode()]);
            $this->addFlash('success', $flashMessage);
            return $this->redirectToRoute('app_shipper');
        }

        return $this->render('shipper/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/shipper/{shipperEntity}/delete', name: 'app_shipper_delete')]
    #[IsGranted('ROLE_EDIT_DIRECTORIES')]
    public function delete(
        Shipper $shipperEntity, 
        ShipperRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        $flashMessage = $translator->trans('Shipper %code% was deleted', ['%code%' => $shipperEntity->getCode()]);
        $this->addFlash('success', $flashMessage);

        $repository->delete($shipperEntity, true);
        return $this->redirectToRoute('app_shipper');
    }
}
