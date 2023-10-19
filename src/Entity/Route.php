<?php

namespace App\Entity;

use App\Entity\Address;
use App\Entity\RouteAddress;
use App\Repository\RouteRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RouteRepository::class)]
class Route
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne]
    private ?Driver $driver = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $time = null;

    #[ORM\ManyToOne]
    private ?Vehicle $vehicle = null;

    #[ORM\Column]
    private ?bool $startFromDepot = true;

    #[ORM\Column]
    private ?bool $endAtDepot = true;

    #[ORM\OneToMany(mappedBy: 'route', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Warehouse $shipFrom = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?MainCompany $company = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\OneToMany(mappedBy: 'route', targetEntity: RouteAddress::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $addresses;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->created = new DateTime();
        $this->status = RouteStatus::NONE->value;
        $this->addresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

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

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): static
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function isStartFromDepot(): ?bool
    {
        return $this->startFromDepot;
    }

    public function setStartFromDepot(bool $startFromDepot): static
    {
        $this->startFromDepot = $startFromDepot;

        return $this;
    }

    public function isEndAtDepot(): ?bool
    {
        return $this->endAtDepot;
    }

    public function setEndAtDepot(bool $endAtDepot): static
    {
        $this->endAtDepot = $endAtDepot;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setRoute($this);

            $this->addAddress($order, $order->getAddressId());
            $this->addAddress($order, $order->getPickupAddressId());
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getRoute() === $this) {
                $order->setRoute(null);
            }

            $this->removeAddressesFromOrder($order->getId());
            $this->updateAddressesPosition();
        }
        return $this;
    }

    public function getShipFrom(): ?Warehouse
    {
        return $this->shipFrom;
    }

    public function setShipFrom(?Warehouse $shipFrom): static
    {
        $this->shipFrom = $shipFrom;

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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, RouteAddress>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    private function isAddressAdded(?Address $address): ?RouteAddress
    {
        foreach($this->addresses as $value) {
            if ($value->getFullAddress() === $address->getFullAddress()) {
                return $value;
            }
        }
        return null;
    }

    private function createRouteAddress(Order $order, Address $address, int $position): RouteAddress
    {
        $newRouteAddress =  new RouteAddress();
        $newRouteAddress->addOrderId($order->getId());
        $newRouteAddress->setPosition($position);
        $newRouteAddress->setFullAddress($address->getFullAddress());
        $newRouteAddress->setLatitude($address->getLatitude());
        $newRouteAddress->setLongitude($address->getLongitude());
        $newRouteAddress->setRoute($this);

        return $newRouteAddress;
    }

    private function addAddress(Order $order, ?Address $address): static
    {
        if (!isset($address)) { 
            return $this;
        }

        $routeAddress = $this->isAddressAdded($address);
        if (isset($routeAddress)) {
            $routeAddress->addOrderId($order->getId());
        } else {
            $lastAddressPosition = $this->addressCount();
            $newAddress = $this->createRouteAddress($order, $address, $lastAddressPosition+1);
            $this->addresses->add($newAddress);
        }

        return $this;
    }

    private function removeAddressesFromOrder(string $orderId): void
    {
        $addressesWithOrderId = $this->addresses->filter(function ($address) use ($orderId) : bool {
            $orderIds = $address->getOrderIds();
            return in_array($orderId, $orderIds);
        });

        foreach($addressesWithOrderId as $address) {
            $orderIds = $address->getOrderIds();
            $orderIds = array_diff($orderIds, [$orderId]);

            if (count($orderIds) === 0) {
                if ($this->addresses->removeElement($address)) {
                    if ($address->getRoute() === $this) {
                        $address->setRoute(null);
                    }
                }
            } else {
                $address->setOrderIds($orderIds);
            }
        }
    }

    private function updateAddressesPosition() 
    {
        $position = 1;
        foreach($this->addresses as $address) {
            $address->setPosition($position);
            $position++;
        }
    }

    public function removeAddress(RouteAddress $address): static
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getRoute() === $this) {
                $address->setRoute(null);
            }
        }
        return $this;
    }

    public function addressCount(): int 
    {
        return count($this->addresses);
    }
}
