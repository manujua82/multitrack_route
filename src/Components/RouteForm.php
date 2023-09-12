<?php

namespace App\Components;

use App\Entity\Route;
use App\Form\RouteType;
use App\Repository\CorrelativesRepository;
use App\Repository\RouteRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\LiveResponder;

#[AsLiveComponent]
class RouteForm extends AbstractController
{
    use ComponentToolsTrait;
    use ComponentWithFormTrait;
    use ValidatableComponentTrait;
    use DefaultActionTrait;

    public Route $route;

    public function __construct(private CorrelativesRepository $correlativesRepository)
    {

    }

    private function getRouteCorrelative(): string
    {
        $correlativeObj = $this->correlativesRepository->getByDocumentType("ROUTE");
        $newRouteCorrelative = $correlativeObj->getNewNumber();
        $this->correlativesRepository->update($correlativeObj, true);
        return $newRouteCorrelative;
    }

    private function getStartTime(): DateTime
    {
        $time = new DateTime();
        $time->setTime(8,0,0);
        return  $time;
    }

    protected function instantiateForm(): FormInterface
    {
        $this->route = new Route();
        $form = $this->createForm(RouteType::class, $this->route);
        $form->get('number')->setData($this->getRouteCorrelative());
        $form->get('date')->setData(new DateTime());
        $form->get('time')->setData($this->getStartTime());
        return $form;
    }

    public function mount(): void
    {

    }

    #[LiveAction]
    public function saveOrder(RouteRepository $repository, LiveResponder $liveResponder)
    {
        // $this->validate();

        // $this->submitForm();
        // $routeEntity = $this->getForm()->getData();
        
        // $date = $this->formValues['date'];
        // $time = $this->formValues['time'];
        
        // $routeEntity->setDate(new DateTime($date));
        // $routeEntity->setTime(new DateTime($date . ' '. $time));

        // dd($routeEntity);
        // $repository->add($routeEntity, true);

        $this->dispatchBrowserEvent('modal:close');
        $this->resetForm();
        // $this->redirectToRoute('app_index');
    }
}