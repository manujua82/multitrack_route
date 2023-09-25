<?php

namespace App\Entity;

use DateTime;
use App\Repository\CorrelativesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CorrelativesRepository::class)]
class Correlatives
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $documentType = null;

    #[ORM\Column(length: 255)]
    private ?string $prefix = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $length = null;

    #[ORM\Column]
    private ?int $lastUsed = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?MainCompany $company = null;

    public function __construct()
    {
        $this->length = 5;
        $this->lastUsed = 0;
        $this->created = new DateTime();
    }

    public function setCorrelative($company, $documentType, $prefix)
    {
        $this->company = $company;
        $this->documentType = $documentType;
        $this->prefix = $prefix;
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

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix): static
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): static
    {
        $this->length = $length;

        return $this;
    }

    public function getLastUsed(): ?int
    {
        return $this->lastUsed;
    }

    public function setLastUsed(int $lastUsed): static
    {
        $this->lastUsed = $lastUsed;

        return $this;
    }

    public function updateLastUsed(): static
    {
        $this->lastUsed = $this->lastUsed + 1;

        return $this;
    }

    public function getNewNumber(): string
    {
        $newNumberStr =  strval($this->lastUsed + 1);
        $newNumberLength = strlen($newNumberStr);

        if ($newNumberLength >= $this->length) {
            return $this->prefix . $newNumberStr;
        }

        $number = "";
        for ($i = $newNumberLength; $i <= $this->length; $i++) {
            $number .= "0";
        }

        return $this->prefix . $number . $newNumberStr;
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
}
