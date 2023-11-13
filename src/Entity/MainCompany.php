<?php

namespace App\Entity;

use App\Repository\MainCompanyRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiResource;


#[ORM\Entity(repositoryClass: MainCompanyRepository::class)]
#[ApiResource]
class MainCompany
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'mainCompany', targetEntity: User::class, orphanRemoval: true)]
    private Collection $Users;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdDate = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Carrier::class, orphanRemoval: true)]
    private Collection $carriers;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Driver::class, orphanRemoval: true)]
    private Collection $drivers;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Warehouse::class, orphanRemoval: true)]
    private Collection $warehouses;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Vehicle::class)]
    private Collection $vehicles;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $billingEmail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contact = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $routeDateFormat = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $routeTimeFormat = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $routeUnitDistance = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $routeUnitWeight = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $routeUnitVolume = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;


    #[ORM\OneToMany(mappedBy: 'company', targetEntity: RoutingSetup::class, orphanRemoval: true)]
    private Collection $routingSetups;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: NotificationSetup::class, orphanRemoval: true)]
    private Collection $notificationSetups;

    public function __construct()
    {
        $this->Users = new ArrayCollection();
        $this->createdDate = new DateTime();
        $this->carriers = new ArrayCollection();
        $this->drivers = new ArrayCollection();
        $this->warehouses = new ArrayCollection();
        $this->vehicles = new ArrayCollection();
        $this->routingSetups = new ArrayCollection();
        $this->routeDateFormat = 'dd.MM.yyyy';
        $this->routeTimeFormat = '12H';
        $this->routeUnitDistance = 'Miles';
        $this->routeUnitWeight = 'Lb';
        $this->routeUnitVolume = 'Pkg';
        $this->notificationSetups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDateFormat(): ?string
    {
        return $this->routeDateFormat;
    }

    public function setDateFormat(?string $routeDateFormat): static
    {
        $this->routeDateFormat = $routeDateFormat;

        return $this;
    }

    public function getTimeFormat(): ?string
    {
        return $this->routeTimeFormat;
    }

    public function setTimeFormat(?string $routeTimeFormat): static
    {
        $this->routeTimeFormat = $routeTimeFormat;

        return $this;
    }

    public function getUnitDistance(): ?string
    {
        return $this->routeUnitDistance;
    }

    public function setUnitDistance(?string $routeUnitDistance): static
    {
        $this->routeUnitDistance = $routeUnitDistance;

        return $this;
    }

    public function getUnitWeight(): ?string
    {
        return $this->routeUnitWeight;
    }

    public function setUnitWeight(?string $routeUnitWeight): static
    {
        $this->routeUnitWeight = $routeUnitWeight;

        return $this;
    }

    public function getUnitVolume(): ?string
    {
        return $this->routeUnitVolume;
    }

    public function setUnitVolume(?string $routeUnitVolume): static
    {
        $this->routeUnitVolume = $routeUnitVolume;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->Users;
    }

    public function addUser(User $user): static
    {
        if (!$this->Users->contains($user)) {
            $this->Users->add($user);
            $user->setMainCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->Users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getMainCompany() === $this) {
                $user->setMainCompany(null);
            }
        }

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): static
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * @return Collection<int, Carrier>
     */
    public function getCarriers(): Collection
    {
        return $this->carriers;
    }

    public function addCarrier(Carrier $carrier): static
    {
        if (!$this->carriers->contains($carrier)) {
            $this->carriers->add($carrier);
            $carrier->setCompany($this);
        }

        return $this;
    }

    public function removeCarrier(Carrier $carrier): static
    {
        if ($this->carriers->removeElement($carrier)) {
            // set the owning side to null (unless already changed)
            if ($carrier->getCompany() === $this) {
                $carrier->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Driver>
     */
    public function getDrivers(): Collection
    {
        return $this->drivers;
    }

    public function addDriver(Driver $driver): static
    {
        if (!$this->drivers->contains($driver)) {
            $this->drivers->add($driver);
            $driver->setCompany($this);
        }

        return $this;
    }

    public function removeDriver(Driver $driver): static
    {
        if ($this->drivers->removeElement($driver)) {
            // set the owning side to null (unless already changed)
            if ($driver->getCompany() === $this) {
                $driver->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Warehouse>
     */
    public function getWarehouses(): Collection
    {
        return $this->warehouses;
    }

    public function addWarehouse(Warehouse $warehouse): static
    {
        if (!$this->warehouses->contains($warehouse)) {
            $this->warehouses->add($warehouse);
            $warehouse->setCompany($this);
        }

        return $this;
    }

    public function removeWarehouse(Warehouse $warehouse): static
    {
        if ($this->warehouses->removeElement($warehouse)) {
            // set the owning side to null (unless already changed)
            if ($warehouse->getCompany() === $this) {
                $warehouse->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Vehicle>
     */
    public function getVehicles(): Collection
    {
        return $this->vehicles;
    }

    public function addVehicle(Vehicle $vehicle): static
    {
        if (!$this->vehicles->contains($vehicle)) {
            $this->vehicles->add($vehicle);
            $vehicle->setCompany($this);
        }

        return $this;
    }

    public function removeVehicle(Vehicle $vehicle): static
    {
        if ($this->vehicles->removeElement($vehicle)) {
            // set the owning side to null (unless already changed)
            if ($vehicle->getCompany() === $this) {
                $vehicle->setCompany(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getBillingEmail(): ?string
    {
        return $this->billingEmail;
    }

    public function setBillingEmail(?string $billingEmail): static
    {
        $this->billingEmail = $billingEmail;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return Collection<int, RoutingSetup>
     */
    public function getRoutingSetups(): Collection
    {
        return $this->routingSetups;
    }

    public function addRoutingSetup(RoutingSetup $routingSetup): static
    {
        if (!$this->routingSetups->contains($routingSetup)) {
            $this->routingSetups->add($routingSetup);
            $routingSetup->setCompany($this);
        }

        return $this;
    }

    public function removeRoutingSetup(RoutingSetup $routingSetup): static
    {
        if ($this->routingSetups->removeElement($routingSetup)) {
            // set the owning side to null (unless already changed)
            if ($routingSetup->getCompany() === $this) {
                $routingSetup->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, NotificationSetup>
     */
    public function getNotificationSetups(): Collection
    {
        return $this->notificationSetups;
    }

    public function addNotificationSetup(NotificationSetup $notificationSetup): static
    {
        if (!$this->notificationSetups->contains($notificationSetup)) {
            $this->notificationSetups->add($notificationSetup);
            $notificationSetup->setCompany($this);
        }

        return $this;
    }

    public function removeNotificationSetup(NotificationSetup $notificationSetup): static
    {
        if ($this->notificationSetups->removeElement($notificationSetup)) {
            // set the owning side to null (unless already changed)
            if ($notificationSetup->getCompany() === $this) {
                $notificationSetup->setCompany(null);
            }
        }

        return $this;
    }
}
