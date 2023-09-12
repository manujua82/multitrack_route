<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $mainOrder = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Item $item = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $unitMeasure = null;

    #[ORM\Column]
    private ?int $qty = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 5)]
    private ?string $price = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 5, nullable: true)]
    private ?string $totalAmount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMainOrder(): ?Order
    {
        return $this->mainOrder;
    }

    public function setMainOrder(?Order $mainOrder): static
    {
        $this->mainOrder = $mainOrder;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getUnitMeasure(): ?string
    {
        return $this->unitMeasure;
    }

    public function setUnitMeasure(?string $unitMeasure): static
    {
        $this->unitMeasure = $unitMeasure;

        return $this;
    }

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setQty(int $qty): static
    {
        $this->qty = $qty;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getTotalAmount(): ?string
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(string $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function __toString() {
        return $this->item->getName();
    }
}
