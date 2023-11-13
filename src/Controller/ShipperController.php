<?php

namespace App\Controller;

use App\Entity\Shipper;
use App\Entity\User;
use App\Form\ShipperType;
use App\Form\UserType;
use App\Repository\ShipperRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ShipperController extends AbstractController
{
    private $mainCompany;

    public function __construct(Security $security)
    {
        $this->mainCompany = $security->getUser()->getMainCompany();
    }

    #[Route('/shipper', name: 'app_shipper')]
    public function index(ShipperRepository $repository): Response
    {
        return $this->render('shipper/index.html.twig', [
            'shippers' => $repository->findAllByCompany(),
        ]);
    }

    #[Route('/shipper/new', name: 'app_shipper_new')]
    public function add(
        Request $request,
        ShipperRepository $shipperRepository,
        TranslatorInterface $translator
    ): Response {
        $form = $this->createForm(ShipperType::class, new Shipper());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newShipper = $form->getData();
            $shipperRepository->add($newShipper, true);

            $flashMessage = $translator->trans('Shipper created flash', ['code' => $newShipper->getCode()]);
            $this->addFlash('success', $flashMessage);
            return $this->redirectToRoute('app_shipper');
        }

        return $this->render('shipper/_form.html.twig', [
            'formShipper' => $form->createView(),
        ]);
    }

    #[Route('/shipper/{shipperEntity}/edit', name: 'app_shipper_edit')]
    public function edit(
        Shipper $shipperEntity,
        Request $request,
        ShipperRepository $repository,
        TranslatorInterface $translator
    ): Response {
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
            'formShipper' => $form->createView(),
            'entity' => $shipperEntity
        ]);
    }

    #[Route('/shipper/{shipperEntity}/delete', name: 'app_shipper_delete')]
    public function delete(
        Shipper $shipperEntity,
        ShipperRepository $shipperRepository,
        UserRepository $userRepository,
        TranslatorInterface $translator
    ): Response {
        $flashMessage = $translator->trans('Shipper %code% was deleted', ['%code%' => $shipperEntity->getCode()]);
        $this->addFlash('success', $flashMessage);

        foreach ($shipperEntity->getUsers() as $user) {
            $userRepository->delete($user, false);
        }
        $shipperRepository->delete($shipperEntity, true);
        return $this->redirectToRoute('app_shipper');
    }

    #[Route('/shipper/{shipperEntity}/addUSer', name: 'app_shipper_add_user')]
    public function newUser(
        Shipper $shipperEntity,
        UserRepository $userRepository,
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        ShipperRepository $shipperRepository
    ): Response {
        $form = $this->createForm(UserType::class, new User());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userEntity = $form->getData();
            $userEntity->setStringRoles($form->get('roleGroup')->getData(), $form->get('rolesUser')->getData());
            $userEntity->setMainCompany($this->mainCompany);
            $userEntity->setPassword(
                $userPasswordHasher->hashPassword(
                    $userEntity,
                    'REGISTER_DEFAULT_USER'
                )
            );
            $userEntity->setAgreedTerms();

            $userRepository->add($userEntity);
            $shipperEntity->addUser($userEntity);
            $shipperRepository->add($shipperEntity, true);
        }

        return $this->render('shipper/new_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/shipper/{userEntity}/editUser', name: 'app_shipper_edit_user')]
    public function editUser(
        User $userEntity,
        Request $request,
        UserRepository $userRepository,
    ): Response {
        $roles = implode(",", $userEntity->getRoles());
        $form = $this->createForm(UserType::class, $userEntity, array("edit" => true, "roles" => $roles));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userEntity = $form->getData();
            $userEntity->setStringRoles($form->get('roleGroup')->getData(), $form->get('rolesUser')->getData());
            $userRepository->add($userEntity);
        }

        $userEntity = $form->getData();
        return $this->render('shipper/new_user.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/shipper/{userEntity}/deleteUser', name: 'app_shipper_delete_user')]
    public function deleteUser(
        User $userEntity,
        Shipper $shipperEntity,
        UserRepository $userRepository,
    ): Response {
        $shipperEntity = $userEntity->getShipper();
        $shipperEntity->removeUser($userEntity);
        $userRepository->delete($userEntity);

        return $this->redirectToRoute('app_shipper');
    }
}
