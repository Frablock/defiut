<?php

// src/Entity/Fichier.php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: 'App\Repository\FichierRepository')]
#[ORM\Table(name: 'Fichier')]
class Fichier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['defi-read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $nom;

    #[ORM\ManyToMany(targetEntity: Defi::class, mappedBy: 'fichiers')]
    private Collection $defis;

    public function __construct()
    {
        $this->defis = new ArrayCollection();
    }

    // Getters et setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDefis(): Collection
    {
        return $this->defis;
    }

    public function addDefi(Defi $defi): self
    {
        if (!$this->defis->contains($defi)) {
            $this->defis[] = $defi;
            $defi->addFichier($this);
        }
        return $this;
    }

    public function removeDefi(Defi $defi): self
    {
        if ($this->defis->removeElement($defi)) {
            $defi->removeFichier($this);
        }
        return $this;
    }
}
