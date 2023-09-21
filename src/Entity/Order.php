<?php

namespace App\Entity;

use DateTime;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $number = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne]
    #[Assert\NotBlank]
    private ?Warehouse $shipFrom = null;

    #[ORM\ManyToOne]
    #[Assert\NotBlank]
    private ?Shipper $shipper = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $barcode = null;

    #[ORM\ManyToOne]
    #[Assert\NotBlank]
    private ?Customer $customerId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customerName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customerEmail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customerPhone = null;

    #[ORM\ManyToOne]
    #[Assert\NotBlank]
    private ?Address $addressId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $addressZone = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $timeFrom = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $timeUntil = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $note = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $serviceTime = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $cod = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $priority = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?MainCompany $company = null;

    #[ORM\OneToMany(mappedBy: 'mainOrder', targetEntity: OrderItem::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $orderItems;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 5, nullable: true)]
    private ?string $weight = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 5, nullable: true)]
    private ?string $volume = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 5, nullable: true)]
    private ?string $pkg = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Route $route = null;

    #[ORM\Column(length: 255, enumType: OrderStatus::class)]
    private ?string $status = null;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
        $this->created = new DateTime();
        $this->date =new DateTime();
        $this->weight = 0.0;
        $this->volume = 0.0;
        $this->pkg = 0.0;
        $this->status = OrderStatus::unschedule;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    public function getShipFrom(): ?Warehouse
    {
        return $this->shipFrom;
    }

    public function setShipFrom(?Warehouse $shipFrom): static
    {
        $this->shipFrom = $shipFrom;

        return $this;
    }

    public function getShipper(): ?Shipper
    {
        return $this->shipper;
    }

    public function setShipper(?Shipper $shipper): static
    {
        $this->shipper = $shipper;

        return $this;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(?string $barcode): static
    {
        $this->barcode = $barcode;

        return $this;
    }

    public function getCustomerId(): ?Customer
    {
        return $this->customerId;
    }

    public function setCustomerId(?Customer $customerId): static
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(?string $customerName): static
    {
        $this->customerName = $customerName;

        return $this;
    }

    public function getContactName(): ?string
    {
        return $this->contactName;
    }

    public function setContactName(?string $contactName): static
    {
        $this->contactName = $contactName;

        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail(?string $customerEmail): static
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    public function getCustomerPhone(): ?string
    {
        return $this->customerPhone;
    }

    public function setCustomerPhone(?string $customerPhone): static
    {
        $this->customerPhone = $customerPhone;

        return $this;
    }

    public function getAddressId(): ?Address
    {
        return $this->addressId;
    }

    public function setAddressId(?Address $addressId): static
    {
        $this->addressId = $addressId;

        return $this;
    }

    public function getAddressZone(): ?string
    {
        return $this->addressZone;
    }

    public function setAddressZone(string $addressZone): static
    {
        $this->addressZone = $addressZone;

        return $this;
    }

    public function getTimeFrom(): ?\DateTimeInterface
    {
        return $this->timeFrom;
    }

    public function setTimeFrom(?\DateTimeInterface $timeFrom): static
    {
        $this->timeFrom = $timeFrom;

        return $this;
    }

    public function getTimeUntil(): ?\DateTimeInterface
    {
        return $this->timeUntil;
    }

    public function setTimeUntil(\DateTimeInterface $timeUntil): static
    {
        $this->timeUntil = $timeUntil;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getServiceTime(): ?string
    {
        return $this->serviceTime;
    }

    public function setServiceTime(?string $serviceTime): static
    {
        $this->serviceTime = $serviceTime;

        return $this;
    }

    public function getCod(): ?string
    {
        return $this->cod;
    }

    public function setCod(?string $cod): static
    {
        $this->cod = $cod;

        return $this;
    }

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function setPriority(?string $priority): static
    {
        $this->priority = $priority;

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

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setMainOrder($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): static
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getMainOrder() === $this) {
                $orderItem->setMainOrder(null);
            }
        }

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getVolume(): ?string
    {
        return $this->volume;
    }

    public function setVolume(?string $volume): static
    {
        $this->volume = $volume;

        return $this;
    }

    public function getPkg(): ?string
    {
        return $this->pkg;
    }

    public function setPkg(?string $pkg): static
    {
        $this->pkg = $pkg;

        return $this;
    }

    public function getRoute(): ?Route
    {
        return $this->route;
    }

    public function setRoute(?Route $route): static
    {
        $this->route = $route;

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
}
