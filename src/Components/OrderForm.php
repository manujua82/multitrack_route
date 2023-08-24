<?php

namespace App\Components;

use App\Entity\Address;
use App\Entity\Customer;
use App\Entity\Item;
use App\Entity\Order;
use App\Entity\Shipper;
use App\Entity\Warehouse;
use App\Repository\OrderRepository;
use DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveResponder;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveArg;

#[AsLiveComponent]
class OrderForm
{
    use ComponentToolsTrait;
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: [
        'number', 
        'type', 
        'barcode',
        'customerName',
        'contactName',
        'customerEmail',
        'customerPhone',
        'addressZone',
        'note',
        'serviceTime',
        'cod',
        'priority',
        'weight',
        'volume',
        'pkg'
    ])]
    #[Valid]
    public Order $order;

    #[LiveProp(writable: true, format: 'Y-m-d')]
    // #[NotBlank]
    public ?DateTime $orderDate = null;

    #[LiveProp (writable: true, format: 'H:i:s')]
    public ?DateTime $timeFrom  = null;

    #[LiveProp (writable: true, format: 'H:i:s')]
    public ?DateTime $timeUntil  = null;

    #[LiveProp (writable: true)]
    // #[NotBlank]
    public ?Customer $customer = null;

    #[LiveProp (writable: true)]
    // #[NotBlank]
    public ?Warehouse $shipFrom = null;

    #[LiveProp (writable: true)]
    // #[NotBlank]
    public ?Shipper $shipper = null;

    #[LiveProp (writable: true)]
    // #[NotBlank]
    public ?Address $address = null;

    #[LiveProp (writable: true)]
    public array $lineItems = [];

     /**
     * A temporary flag that we just saved.
     *
     * This doesn't need to be a LiveProp because it's set in a LiveAction,
     * rendered immediately, then we want it to be forgotten.
     */
    public bool $savedSuccessfully = false;
    public bool $saveFailed = false;

    public function __construct()
    {
        $this->orderDate = new DateTime();
    }

    // add mount method
    public function mount(Order $order): void
    {
        $this->order = $order;
    }

    #[LiveAction]
    public function onCustomerChanged(): void
    {
        if ($this->customer) {
            $customer = $this->customer;
            $this->order->setCustomerName($customer->getName());
            $this->order->setContactName($customer->getContact());
            $this->order->setCustomerPhone($customer->getPhone());
            $this->order->setCustomerEmail($customer->getEmail());
            $this->timeFrom = $customer->getTimeFrom();
            $this->timeUntil = $customer->getTimeUntil();
        }
    }

    #[LiveAction]
    public function addLineItem(): void
    {
        $this->lineItems[] = [
            'itemId' => null,
            'description' => '',
            'unitMeasure' => "pcs.",
            'qty' => 1,
            'price' => 0,
            'amount' => 0,
            'isEditing' => true,
        ];
    }

    #[LiveListener('line_item:save')]
    public function saveLineItem(
        #[LiveArg] int $key,
        #[LiveArg] Item $item,
        #[LiveArg] string $description, 
        #[LiveArg] string $unitMeasure, 
        #[LiveArg] int $qty,
        #[LiveArg] float $price,
        #[LiveArg] float $amount,
    ): void
    {
        
        if (!isset($this->lineItems[$key])) {
            // shouldn't happen
            return;
        }

        $this->lineItems[$key]['productId'] = $item->getId();
        $this->lineItems[$key]['description'] = $description;
        $this->lineItems[$key]['unitMeasure'] = $unitMeasure;
        $this->lineItems[$key]['qty'] = $qty;
        $this->lineItems[$key]['price'] = $price;
        $this->lineItems[$key]['amount'] = $amount;
    }

    #[LiveListener('line_item:change_edit_mode')]
    public function onLineItemEditModeChange(#[LiveArg] int $key, #[LiveArg] $isEditing): void
    {
        $this->lineItems[$key]['isEditing'] = $isEditing;
    }

    #[LiveListener('removeLineItem')]
    public function removeLineItem(#[LiveArg] int $key): void
    {
        unset($this->lineItems[$key]);
    }


    #[ExposeInTemplate]
    public function getTypes(): array
    {
        return array(
            "Delivery",
            "Collection",
            "Pickup & Delivery"
        );
    }

    #[ExposeInTemplate]
    public function getPriorities(): array
    {
        return array(
            "High",
            "Normal",
            "Low"
        );
    }

    #[LiveAction]
    public function saveOrder(OrderRepository $repository, LiveResponder $liveResponder): void
    {
       
        $this->order->setDate($this->orderDate);
        
        if ($this->customer) {
            $this->order->setCustomerId($this->customer);
        }
        if ($this->shipper) {
            $this->order->setShipper($this->shipper);
        }
        if ($this->shipFrom) {
            $this->order->setShipFrom($this->shipFrom);
        }
        if ($this->address) {
            $this->order->setAddressId($this->address);
        }
        if ($this->timeFrom){
            $this->order->setTimeFrom($this->timeFrom);
        }
        if ($this->timeUntil) {
            $this->order->setTimeUntil($this->timeUntil);
        }

        $this->validate();
       
        $repository->add($this->order, true);

        // dd($this->order);
        
     
    }
}