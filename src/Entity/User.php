<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Please enter an email')]
    #[Assert\Email(message: 'The email {{ value }} is not a valid email.')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Driver $driver = null;

    #[ORM\ManyToOne(inversedBy: 'Users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MainCompany $mainCompany = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column]
    private ?bool $active = true;

    #[ORM\Column]
    private ?bool $deleted = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $roleGroup = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $bannedUntil = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE,  nullable: true)]
    private ?\DateTimeInterface $agreedTermsAt = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function setStringRoles(string $roleGroup, string $roles): static
    {
        if ($roleGroup == 'admin') {
            $roles = ['ROLE_ADMIN'];
        } else {
            $roles = explode(",", $roles);
        }
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoleGroup(): ?string
    {
        return $this->roleGroup;
    }

    public function setRoleGroup(?string $roleGroup): static
    {
        $this->roleGroup = $roleGroup;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(Driver $driver): static
    {
        // set the owning side of the relation if necessary
        if ($driver->getUser() !== $this) {
            $driver->setUser($this);
        }

        $this->driver = $driver;

        return $this;
    }

    public function getMainCompany(): ?MainCompany
    {
        return $this->mainCompany;
    }

    public function setMainCompany(?MainCompany $mainCompany): static
    {
        $this->mainCompany = $mainCompany;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): static
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getBannedUntil(): ?\DateTimeInterface
    {
        return $this->bannedUntil;
    }

    public function setBannedUntil(?\DateTimeInterface $bannedUntil): static
    {
        $this->bannedUntil = $bannedUntil;

        return $this;
    }

    public function getAgreedTermsAt(): ?\DateTimeInterface
    {
        return $this->agreedTermsAt;
    }

    public function setAgreedTerms(): void
    {
        $this->agreedTermsAt = new DateTime();
    }
}
