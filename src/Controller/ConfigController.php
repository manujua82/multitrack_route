<?php

namespace App\Controller;

use App\Repository\MainCompanyRepository;
use App\Form\ConfigType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ConfigController extends AbstractController
{
    #[Route('/config', name: 'app_config')]
    public function index(
        Request $request,
        MainCompanyRepository $repository,
        TranslatorInterface $translator,
        SluggerInterface $slugger
    ): Response {
        $mainCompanyEntity = $repository->config();
        $fileAct = $mainCompanyEntity->getPhoto();
        $form = $this->createForm(ConfigType::class, $mainCompanyEntity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mainCompanyEntity = $form->getData();
            $photoFile = $form->get('photo')->getData();
            $deleteImage = $form->get('delete');
            $directory = $this->getParameter('photo_directory');
            $fileRemove = $directory . '/' . $fileAct;

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = pathinfo($photoFile->getClientOriginalName(), PATHINFO_EXTENSION);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $extension;

                try {
                    $photoFile->move(
                        $directory,
                        $newFilename
                    );
                    if (is_file($fileRemove)) {
                        unlink($fileRemove);
                    }
                } catch (FileException $e) {
                }
                $mainCompanyEntity->setPhoto($newFilename);
            } else if ($deleteImage) {
                if (is_file($fileRemove)) {
                    unlink($fileRemove);
                    $mainCompanyEntity->setPhoto("");
                }
            }

            $repository->add($mainCompanyEntity, true);
            $flashMessage = $translator->trans('Setup successfully saved');
            $this->addFlash('success', $flashMessage);

            return $this->redirectToRoute('app_config');
        }

        return $this->render('Config/config.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
