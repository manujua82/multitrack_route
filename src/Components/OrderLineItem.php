<?php

namespace App\Components;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveResponder;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;


#[AsLiveComponent()]
class OrderLineItem
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp]
    public int $key;

    #[LiveProp(writable: true)]
    #[Assert\NotNull]
    public ?Item $item = null;

    #[LiveProp(writable: true)]
    public ?string $unitMeasure = '';

    #[LiveProp(writable: true)]
    #[Assert\Positive]
    public int $qty = 1;

    #[LiveProp(writable: true)]
    #[Assert\Positive]
    public float $price = 0;

    #[LiveProp()]
    public float $amount = 0;

    #[LiveProp()]
    public bool $isEditing = false;

    private int $count = 0;

    public function __construct(private ItemRepository $itemRepository)
    {
    }

    public function mount(?int $itemId): void
    {
        $this->price = $this->count;
        $this->count++;
        if  ($this->price > 0){
            dd($itemId);
        }
        if ($itemId) {
            $this->item = $this->itemRepository->find($itemId);
            dd($itemId);
        }
    }

    #[LiveAction]
    public function save(LiveResponder $responder): void
    {        
        $this->validate();
        $responder->emitUp('line_item:save',[
            'key' => $this->key,
            'item' => $this->item->getId(),
            'unitMeasure' =>  $this->unitMeasure,
            'qty' => $this->qty,
            'price' => $this->price,
            'amount' => $this->amount,
        ]);
    
        $this->changeEditMode(false, $responder);
    }

    #[LiveAction]
    public function edit(LiveResponder $responder): void
    {
        $this->changeEditMode(true, $responder);
    }

    #[LiveAction]
    public function onItemChanged(): void
    {
        if ($this->item) {
            $item = $this->item;
            $this->unitMeasure = $item->getUnit();
            $this->price = $item->getPrice();
            $this->calculateAmount();
        }
    }

    #[LiveAction]
    public function calculateAmount(): void
    {
        $this->amount = $this->price * $this->qty;
    }

    private function changeEditMode(bool $isEditing, LiveResponder $responder): void
    {
        $this->isEditing = $isEditing;

        // emit to InvoiceCreator so it can track which items are being edited
        $responder->emitUp('line_item:change_edit_mode', [
            'key' => $this->key,
            'isEditing' => $this->isEditing,
        ]);
    }
}