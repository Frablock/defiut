<?php
// src/Entity/User.php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

use DateTime;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'Utilisateur')]
#[UniqueEntity(fields: ['email'], message: 'Il y a déjà un compte avec ce mail')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $mail;

    #[ORM\Column(name: 'mot_de_passe', type: 'string', length: 255)]
    private $password;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private $scoreTotal = 0;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $creationDate;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $lastCo;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private bool $isVerified = false;

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

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Defi::class, cascade: ['persist', 'remove'])]
    private Collection $defis;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: RecentDefi::class, cascade: ['persist', 'remove'])]
    private Collection $recentDefis;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DefiValidUtilisateur::class, cascade: ['persist', 'remove'])]
    private Collection $defiValidUtilisateurs;


    public function __construct()
    {
        $this->defis = new ArrayCollection();
        $this->recentDefis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->mail;
    }

    public function setEmail(string $email): self
    {
        $this->mail = $email;
        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $email): self
    {
        $this->mail = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function setMotDePasse(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function GetMotDePasse(): ?string
    {
        return $this->password;
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

    // Added defis relationship methods
    public function getDefis(): Collection
    {
        return $this->defis;
    }

    public function addDefi(Defi $defi): self
    {
        if (!$this->defis->contains($defi)) {
            $this->defis->add($defi);
            $defi->setUser($this);
        }
        return $this;
    }

    public function removeDefi(Defi $defi): self
    {
        if ($this->defis->removeElement($defi)) {
            if ($defi->getUser() === $this) {
                $defi->setUser(null);
            }
        }
        return $this;
    }

    // Fixed recentDefis methods
    public function getRecentDefis(): Collection
    {
        return $this->recentDefis;
    }

    public function addRecentDefi(RecentDefi $recentDefi): self
    {
        if (!$this->recentDefis->contains($recentDefi)) {
            $this->recentDefis->add($recentDefi);
            $recentDefi->setUser($this);
        }
        return $this;
    }

    public function removeRecentDefi(RecentDefi $recentDefi): self
    {
        if ($this->recentDefis->removeElement($recentDefi)) {
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
        return (string) $this->mail;
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

    // Fixed recentDefis methods
    public function getDefiValidUtilisateurs(): Collection
    {
        return $this->defiValidUtilisateurs;
    }

    public function addDefiValidUtilisateurs(DefiValidUtilisateur $defiValidUtilisateur): self
    {
        if (!$this->defiValidUtilisateurs->contains($defiValidUtilisateur)) {
            $this->defiValidUtilisateurs->add($defiValidUtilisateur);
            $defiValidUtilisateur->setUser($this);
        }
        return $this;
    }

    public function removeDefiValidUtilisateurs(DefiValidUtilisateur $defiValidUtilisateur): self
    {
        if ($this->defiValidUtilisateurs->removeElement($defiValidUtilisateur)) {
            if ($defiValidUtilisateur->getUser() === $this) {
                $defiValidUtilisateur->setUser(null);
            }
        }
        return $this;
    }
}