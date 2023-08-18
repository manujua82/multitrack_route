<?php

namespace App\Components;

use App\Entity\Address;
use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\Shipper;
use App\Entity\Warehouse;
use App\Repository\OrderRepository;
use DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveResponder;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
class OrderForm
{
    use ComponentToolsTrait;
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: true)]
    #[NotBlank]
    public string $number = '';

    #[LiveProp(writable: true)]
    public string $date = '';

    #[LiveProp(writable: true)]
    #[NotBlank]
    public ?string $type = "Delivery";

    #[LiveProp(writable: true)]
    public ?Warehouse $shipFrom = null;

    #[LiveProp(writable: true)]
    public ?Customer $customer = null;

    #[LiveProp(writable: true)]
    public ?Shipper $shipper = null;

    #[LiveProp(writable: true)]
    public ?Address $address = null;

    #[LiveProp(writable: true)]
    public ?string $addressZone = null;

    #[LiveProp(writable: true)]
    public ?string $customerName = '';

    #[LiveProp(writable: true)]
    public ?string $customerContact = '';

    #[LiveProp(writable: true)]
    public ?string $customerPhone = '';

    #[LiveProp(writable: true)]
    public ?string $customerEmail = '';

    #[LiveProp(writable: true)]
    public ?string $note = '';

    #[LiveProp(writable: true)]
    public ?string $priority = '';

    #[LiveProp(writable: true)]
    public ?string $barcode = '';

    #[LiveProp(writable: true)]
    public string $timeFrom = '';

    #[LiveProp(writable: true)]
    public string $timeUntil = '';

    #[LiveProp(writable: true)]
    public int $serviceTime = 0;

    #[LiveProp(writable: true)]
    public float $cod = 0;

    #[LiveProp(writable: true)]
    public float $weight = 0;

    #[LiveProp(writable: true)]
    public float $volume = 0;

    #[LiveProp(writable: true)]
    public float $pkg = 0;

    public function __construct()
    {
        // $this->date = new DateTime();
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
        // dd($repository);
    }
}