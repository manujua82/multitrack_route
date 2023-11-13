<?php

namespace App\Entity;

use App\Repository\RoutingSetupRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: RoutingSetupRepository::class)]
#[ApiResource]
class RoutingSetup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column]
    private ?int $stopDuration = null;

    #[ORM\Column(nullable: true)]
    private ?bool $startFromDeport = null;

    #[ORM\Column(nullable: true)]
    private ?bool $endFromDepot = null;

    #[ORM\Column(nullable: true)]
    private ?bool $driverHomeLocation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $costPerDistance = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $costPerHour = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $baseFare = null;

    #[ORM\ManyToOne(inversedBy: 'routingSetups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MainCompany $company = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getStopDuration(): ?int
    {
        return $this->stopDuration;
    }

    public function setStopDuration(int $stopDuration): static
    {
        $this->stopDuration = $stopDuration;

        return $this;
    }

    public function isStartFromDeport(): ?bool
    {
        return $this->startFromDeport;
    }

    public function setStartFromDeport(?bool $startFromDeport): static
    {
        $this->startFromDeport = $startFromDeport;

        return $this;
    }

    public function isEndFromDepot(): ?bool
    {
        return $this->endFromDepot;
    }

    public function setEndFromDepot(?bool $endFromDepot): static
    {
        $this->endFromDepot = $endFromDepot;

        return $this;
    }

    public function isDriverHomeLocation(): ?bool
    {
        return $this->driverHomeLocation;
    }

    public function setDriverHomeLocation(?bool $driverHomeLocation): static
    {
        $this->driverHomeLocation = $driverHomeLocation;

        return $this;
    }

    public function getCostPerDistance(): ?string
    {
        return $this->costPerDistance;
    }

    public function setCostPerDistance(?string $costPerDistance): static
    {
        $this->costPerDistance = $costPerDistance;

        return $this;
    }

    public function getCostPerHour(): ?string
    {
        return $this->costPerHour;
    }

    public function setCostPerHour(?string $costPerHour): static
    {
        $this->costPerHour = $costPerHour;

        return $this;
    }

    public function getBaseFare(): ?string
    {
        return $this->baseFare;
    }

    public function setBaseFare(?string $baseFare): static
    {
        $this->baseFare = $baseFare;

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
}
