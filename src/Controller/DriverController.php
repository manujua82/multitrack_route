<?php

namespace App\Controller;

use App\Entity\Driver;
use App\Form\DriverType;
use App\Repository\DriverRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class DriverController extends AbstractController
{
    #[Route('/driver', name: 'app_driver')]
    public function index(DriverRepository $driverRepository): Response
    {
        return $this->render('driver/index.html.twig', [
            'drivers' => $driverRepository->findAllByCompany(),
        ]);
    }

    #[Route('/driver/new', name: 'app_driver_new', priority:  2)]
    public function add(
        Request $request,
        DriverRepository $driverRepository,
        UserRepository $userRepository,
        TranslatorInterface $translator,
    ): Response {
        $form = $this->createForm(DriverType::class, new Driver());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $driverEntity = $form->getData();
            $company = $this->getUser()->getMainCompany();
           
           
            $driverUser = $userRepository->createUser(
                $driverEntity->getEmail(),
                $form->get('plainPassword')->getData(),
                $company,
                ['ROLE_DRIVER']
            );

            
            $driverEntity->setUser($driverUser);
            $driverRepository->add($driverEntity, true);

            $flashMessage = $translator->trans('Driver new flash', ['code' => $driverEntity->getName()]);
            $this->addFlash('success', $flashMessage);
            return $this->redirectToRoute('app_driver');
        }

        return $this->render('driver/new.html.twig', [
            'form' => $form->createView(),
            'currentPassword' => ''
        ]);
    }

    #[Route('/driver/{driverEntity}/edit', name: 'app_driver_edit')]
    public function edit(
        Driver $driverEntity,
        Request $request,
        DriverRepository $driverRepository,
        UserRepository $userRepository,
        TranslatorInterface $translator,
    ): Response {
        $form = $this->createForm(DriverType::class, $driverEntity, array("require_pass" => false));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //TODO: Added changes for user passwords and/or email

            $driverEntity = $form->getData();
            $driverRepository->add($driverEntity, true);

            if ($form->get('plainPassword')->getData()) {
                $userRepository->upgradePassword($driverEntity->getUser(), $form->get('plainPassword')->getData());
            }

            $flashMessage = $translator->trans('Driver edit flash', ['code' => $driverEntity->getName()]);
            $this->addFlash('success', $flashMessage);
            return $this->redirectToRoute('app_driver');
        }

        $driverEntity = $form->getData();
        return $this->render('driver/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/driver/{driverEntity}/delete', name: 'app_driver_delete')]
    public function delete(Driver $driverEntity, Request $request, DriverRepository $driverRepository): Response
    {
        $driverRepository->delete($driverEntity, true);
        return $this->redirectToRoute('app_driver');
    }
}
