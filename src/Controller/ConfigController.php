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
        $form = $this->createForm(ConfigType::class, $mainCompanyEntity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mainCompanyEntity = $form->getData();
            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = pathinfo($photoFile->getClientOriginalName(), PATHINFO_EXTENSION);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $extension;

                try {
                    $photoFile->move(
                        $this->getParameter('photo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $mainCompanyEntity->setPhoto($newFilename);
            }

            $repository->add($mainCompanyEntity, true);
            $flashMessage = $translator->trans('Company edited');
            $this->addFlash('success', $flashMessage);

            return $this->redirectToRoute('app_config');
        }

        return $this->render('Config/config.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
