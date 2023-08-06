<?php

namespace App\Controller;

use App\Entity\ReasonRejection;
use App\Form\ReasonRejectionType;
use App\Repository\ReasonRejectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReasonRejectionController extends AbstractController
{
    private $TYPES = [
        'SITE' => 'SITE',
        'GOOD' => 'GOOD',
        'ORDER' => 'ORDER'
    ];

    private $REDIRECT = [
        'SITE' => 'app_reason_rejection_site',
        'GOOD' => 'app_reason_rejection_good',
        'ORDER' => 'app_reason_rejection_order'
    ];

    #[Route('/reason/site', name: 'app_reason_rejection_site')]
    public function siteReason(ReasonRejectionRepository $repository): Response
    {
        return $this->render('reason_rejection/side.html.twig', [
            'reasons' => $repository->findAllByReasonType($this->TYPES['SITE']),
        ]);
    }

    #[Route('/reason/order', name: 'app_reason_rejection_order')]
    public function orderReason(ReasonRejectionRepository $repository): Response
    {
        return $this->render('reason_rejection/order.html.twig', [
            'reasons' => $repository->findAllByReasonType($this->TYPES['ORDER']),
        ]);
    }

    #[Route('/reason/good', name: 'app_reason_rejection_good')]
    public function orderGood(ReasonRejectionRepository $repository): Response
    {
        return $this->render('reason_rejection/good.html.twig', [
            'reasons' => $repository->findAllByReasonType($this->TYPES['GOOD']),
        ]);
    }

    private function newReasonByType($type, $request,  $repository, $translator) {
        $form = $this->createForm(ReasonRejectionType::class, new ReasonRejection(), [
            'empty_data' => $type
        ]);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {    
            $reasonEntity = $form->getData();
            $repository->add($reasonEntity, true);   

            $flashMessage = $translator->trans('Reason %name% was created', ['%name%' => $reasonEntity->getName()]);
            $this->addFlash('success', $flashMessage);
            return $this->redirectToRoute($this->REDIRECT[$type]);
        }
        return $this->render('reason_rejection/new.html.twig', [
            'form' => $form->createView(),
            'cancelPath' => $this->REDIRECT[$type]
        ]);
    }

    #[Route('/reason/site/new', name: 'app_reason_rejection_newSite')]
    public function newSiteReason(
        Request $request,
        ReasonRejectionRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        return $this->newReasonByType(
            $this->TYPES['SITE'], 
            $request,
            $repository,
            $translator
        );
    }

    #[Route('/reason/order/new', name: 'app_reason_rejection_newOrder')]
    public function newOrderReason(
        Request $request,
        ReasonRejectionRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        return $this->newReasonByType(
            $this->TYPES['ORDER'], 
            $request,
            $repository,
            $translator
        );
    }

    #[Route('/reason/good/new', name: 'app_reason_rejection_newGood')]
    public function newGoodReason(
        Request $request,
        ReasonRejectionRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        return $this->newReasonByType(
            $this->TYPES['GOOD'], 
            $request,
            $repository,
            $translator
        );
    }

    #[Route('/reason/{reasonEntity}/edit', name: 'app_reason_rejection_edit')]
    public function edit(
        ReasonRejection $reasonEntity,
        Request $request,
        ReasonRejectionRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        $reasonType = $reasonEntity->getType();
        $form = $this->createForm(ReasonRejectionType::class, $reasonEntity, [
            'empty_data' => $reasonType
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $reasonEntity = $form->getData();
            $repository->add($reasonEntity, true);   

            $flashMessage = $translator->trans('Reason %name% was edited', ['%name%' => $reasonEntity->getName()]);
            $this->addFlash('success', $flashMessage);
            return $this->redirectToRoute($this->REDIRECT[$reasonType]);
        }
        return $this->render('reason_rejection/edit.html.twig', [
            'form' => $form->createView(),
            'cancelPath' => $this->REDIRECT[$reasonType]
        ]);
    }

    #[Route('/reason/{reasonEntity}/delete', name: 'app_reason_rejection_delete')]
    public function delete(
        ReasonRejection $reasonEntity,
        ReasonRejectionRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        $flashMessage = $translator->trans('Reason of rejection %name% was deleted', ['%name%' => $reasonEntity->getName()]);
        $this->addFlash('success', $flashMessage);

        $repository->delete($reasonEntity, true);
        return $this->redirectToRoute('app_reason_rejection');
    }
}
