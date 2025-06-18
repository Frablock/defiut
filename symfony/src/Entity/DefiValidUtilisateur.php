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

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'defiValidUtilisateurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Defi::class, inversedBy: 'defiValidUtilisateurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Defi $defi = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateValid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getDefi(): ?User
    {
        return $this->defi;
    }

    public function setDefi(?Defi $defi): self
    {
        $this->defi = $defi;
        return $this;
    }

    public function getDateValid(): ?User
    {
        return $this->dateValid;
    }

    public function setDateValid(?\DateTimeInterface $dateValid): self
    {
        $this->dateValid = $dateValid;
        return $this;
    }
}
