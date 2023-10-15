<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Knp\Component\Pager\PaginatorInterface;


class ItemController extends AbstractController
{
    #[Route('/item', name: 'app_item')]
    public function index(Request $request, ItemRepository $repository,  PaginatorInterface $paginator): Response
    {
        $parent = "";
        if ($request->query->get('query')) {
            $parent = $request->query->get('query');
        }

        $queryBuilder = $repository->getSearchByParentQueryBuilder($parent);
        
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );

        if ($request->query->get('preview') &&  !$request->query->get('page')) {
            return $this->render('item/list.html.twig', [
                'pagination' => $pagination,
            ]);
        }

        return $this->render('item/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/item/new', name: 'app_item_new')]
    public function add(
        Request $request, 
        ItemRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        $form = $this->createForm(ItemType::class, new Item());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $itemEntity = $form->getData();
            $repository->add($itemEntity, true);   

            $flashMessage = $translator->trans('Item %code% was created', ['%code%' => $itemEntity->getCode()]);
            $this->addFlash('success', $flashMessage);
            return $this->redirectToRoute('app_item');
        }

        return $this->render('item/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/item/{itemEntity}/edit', name: 'app_item_edit')]
    public function edit(
        Item $itemEntity, 
        Request $request, 
        ItemRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        $form = $this->createForm(ItemType::class, $itemEntity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $itemEntity = $form->getData();
            $repository->add($itemEntity, true); 

            $flashMessage = $translator->trans('Item %code% edit', ['%code%' => $itemEntity->getCode()]);
            $this->addFlash('success', $flashMessage);

            return $this->redirectToRoute('app_item');
        }

        return $this->render('item/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/item/{itemEntity}/delete', name: 'app_item_delete')]
    public function delete(
        Item $itemEntity, 
        Request $request, 
        ItemRepository $repository,
        TranslatorInterface $translator
    ): Response
    {
        $flashMessage = $translator->trans('Item %code% was deleted', ['%code%' => $itemEntity->getCode()]);
        $this->addFlash('success', $flashMessage);

        $repository->delete($itemEntity, true);
        return $this->redirectToRoute('app_item');
    }
}
