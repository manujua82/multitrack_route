<?php

namespace App\Entity;

use App\Repository\NotificationSetupRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationSetupRepository::class)]
class NotificationSetup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $documentType = null;

    #[ORM\Column(length: 255)]
    private ?string $documentStatus = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailSubject = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $emailBody = null;

    #[ORM\ManyToOne(inversedBy: 'notificationSetups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MainCompany $company = null;

    public function __construct($documentType, $documentStatus = "none")
    {
        $this->documentType = $documentType;
        $this->documentStatus = $documentStatus;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDocumentType(): ?string
    {
        return $this->documentType;
    }

    public function setDocumentType(string $documentType): static
    {
        $this->documentType = $documentType;

        return $this;
    }

    public function getDocumentStatus(): ?string
    {
        return $this->documentStatus;
    }

    public function setDocumentStatus(string $documentStatus): static
    {
        $this->documentStatus = $documentStatus;

        return $this;
    }

    public function getEmailSubject(): ?string
    {
        return $this->emailSubject;
    }

    public function setEmailSubject(?string $emailSubject): static
    {
        $this->emailSubject = $emailSubject;

        return $this;
    }

    public function getEmailBody(): ?string
    {
        return $this->emailBody;
    }

    public function setEmailBody(?string $emailBody): static
    {
        $this->emailBody = $emailBody;

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
