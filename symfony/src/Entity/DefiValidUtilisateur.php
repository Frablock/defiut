<?php

namespace App\Entity;

use App\Repository\DefiValidUtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DefiValidUtilisateurRepository::class)]
class DefiValidUtilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'defiValidUtilisateur')]
    private Collection $userId;

    /**
     * @var Collection<int, Defi>
     */
    #[ORM\OneToMany(targetEntity: Defi::class, mappedBy: 'defiValidUtilisateur')]
    private Collection $defiId;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateValid = null;

    public function __construct()
    {
        $this->userId = new ArrayCollection();
        $this->defiId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserId(): Collection
    {
        return $this->userId;
    }

    public function addUserId(User $userId): static
    {
        if (!$this->userId->contains($userId)) {
            $this->userId->add($userId);
            $userId->setDefiValidUtilisateur($this);
        }

        return $this;
    }

    public function removeUserId(User $userId): static
    {
        if ($this->userId->removeElement($userId)) {
            // set the owning side to null (unless already changed)
            if ($userId->getDefiValidUtilisateur() === $this) {
                $userId->setDefiValidUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Defi>
     */
    public function getDefiId(): Collection
    {
        return $this->defiId;
    }

    public function addDefiId(Defi $defiId): static
    {
        if (!$this->defiId->contains($defiId)) {
            $this->defiId->add($defiId);
            $defiId->setDefiValidUtilisateur($this);
        }

        return $this;
    }

    public function removeDefiId(Defi $defiId): static
    {
        if ($this->defiId->removeElement($defiId)) {
            // set the owning side to null (unless already changed)
            if ($defiId->getDefiValidUtilisateur() === $this) {
                $defiId->setDefiValidUtilisateur(null);
            }
        }

        return $this;
    }

    public function getDateValid(): ?\DateTimeInterface
    {
        return $this->dateValid;
    }

    public function setDateValid(\DateTimeInterface $dateValid): static
    {
        $this->dateValid = $dateValid;

        return $this;
    }
}
