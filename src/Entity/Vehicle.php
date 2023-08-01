<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Number = null;

    #[ORM\Column(nullable: true)]
    private ?float $weight = null;

    #[ORM\Column(nullable: true)]
    private ?float $volume = null;

    #[ORM\Column(nullable: true)]
    private ?float $plt = null;

    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Warehouse $depot = null;

    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Carrier $carrier = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    private ?MainCompany $company = null;

    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    private ?Driver $driver = null;

    public function __construct()
    {
        $this->created = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->Number;
    }

    public function setNumber(string $Number): static
    {
        $this->Number = $Number;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getVolume(): ?float
    {
        return $this->volume;
    }

    public function setVolume(?float $volume): static
    {
        $this->volume = $volume;

        return $this;
    }

    public function getPlt(): ?float
    {
        return $this->plt;
    }

    public function setPlt(?float $plt): static
    {
        $this->plt = $plt;

        return $this;
    }

    public function getDepot(): ?Warehouse
    {
        return $this->depot;
    }

    public function setDepot(?Warehouse $depot): static
    {
        $this->depot = $depot;

        return $this;
    }

    public function getCarrier(): ?Carrier
    {
        return $this->carrier;
    }

    public function setCarrier(?Carrier $carrier): static
    {
        $this->carrier = $carrier;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function getCompany(): ?MainCompany
    {
        return $this->company;
    }

    public function setCompany(?MainCompany $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(?Driver $driver): static
    {
        $this->driver = $driver;

        return $this;
    }
}
