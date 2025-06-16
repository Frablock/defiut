<?php

// src/Entity/User.php
namespace App\Entity;

use Doctrine\Collections\ArrayCollection;
use Doctrine\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

use DateTime;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'Utilisateur')]
#[UniqueEntity(fields: ['mail'], message: 'Il y a déjà un compte avec ce mail')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $mail;

    #[ORM\Column(name: 'mot_de_passe', type: 'string', length: 255)]
    private $motDePasse;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private $scoreTotal = 0;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $creationDate;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $lastCo;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private bool $isVerified = false;

    #[ORM\OneToMany(targetEntity: Defi::class, mappedBy: 'user')]
    private $defis;

    #[ORM\OneToMany(targetEntity: RecentDefi::class, mappedBy: 'user')]
    private $recentDefis;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastTryDate = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $tokenExpirationDate = null;


    // Getters et setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;
        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;
        return $this;
    }

    public function getScoreTotal(): ?int
    {
        return $this->scoreTotal;
    }

    public function setScoreTotal(int $scoreTotal): self
    {
        $this->scoreTotal = $scoreTotal;
        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function getLastCo(): ?\DateTimeInterface
    {
        return $this->lastCo;
    }

    public function setLastCo(\DateTimeInterface $lastCo): self
    {
        $this->lastCo = $lastCo;
        return $this;
    }

    /**
     * @return Collection|Defi[]
     */
    public function getDefis(): Collection
    {
        return $this->defis;
    }

    public function addDefi(Defi $defi): self
    {
        if (!$this->defis->contains($defi)) {
            $this->defis[] = $defi;
            $defi->setUser($this);
        }
        return $this;
    }

    public function removeDefi(Defi $defi): self
    {
        if ($this->defis->contains($defi)) {
            $this->defis->removeElement($defi);
            // set the owning side to null (unless already changed)
            if ($defi->getUser() === $this) {
                $defi->setUser(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|RecentDefi[]
     */
    public function getRecentDefis(): Collection
    {
        return $this->recentDefis;
    }

    public function addRecentDefi(RecentDefi $recentDefi): self
    {
        if (!$this->recentDefis->contains($recentDefi)) {
            $this->recentDefis[] = $recentDefi;
            $recentDefi->setUser($this);
        }
        return $this;
    }

    public function removeRecentDefi(RecentDefi $recentDefi): self
    {
        if ($this->recentDefis->contains($recentDefi)) {
            $this->recentDefis->removeElement($recentDefi);
            // set the owning side to null (unless already changed)
            if ($recentDefi->getUser() === $this) {
                $recentDefi->setUser(null);
            }
        }
        return $this;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
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

    public function setRoles(array $roles): self
    {
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    // Add the getter method
    public function getEmail(): string
    {
        return $this->mail;
    }

    // Optional setter if needed
    public function setEmail(string $email): void
    {
        $this->mail = $email;
    }

    public function getLastTryDate(): ?\DateTimeInterface
    {
        return $this->lastTryDate;
    }

    public function setLastTryDate(?\DateTimeInterface $lastTryDate): static
    {
        $this->lastTryDate = $lastTryDate;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getTokenExpirationDate(): ?\DateTimeInterface
    {
        return $this->tokenExpirationDate;
    }

    public function isTokenvalid(): bool
    {
        return $this->tokenExpirationDate > new DateTime();
    }

    public function setTokenExpirationDate(?\DateTimeInterface $tokenExpirationDate): static
    {
        $this->tokenExpirationDate = $tokenExpirationDate;

        return $this;
    }
}
