<?php

// src/Entity/Indice.php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: 'App\Repository\IndiceRepository')]
#[ORM\Table(name: 'Indice')]
class Indice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Groups(['defi-read'])]
    #[ORM\Column(type: 'text')]
    private string $contenu;

    #[ORM\ManyToMany(targetEntity: Defi::class)]
    private Collection $defiIndices;

    public function __construct()
    {
        $this->defiIndices = new ArrayCollection();
    }

    // Getters et setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function getDefiIndices(): Collection
    {
        return $this->defiIndices;
    }

    public function addDefiIndice(DefiIndice $defiIndice): self
    {
        if (!$this->defiIndices->contains($defiIndice)) {
            $this->defiIndices[] = $defiIndice;
            $defiIndice->setIndice($this);
        }
        return $this;
    }
}
