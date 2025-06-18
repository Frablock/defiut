<?php

// src/Entity/DefiIndice.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: 'App\Repository\DefiIndiceRepository')]
#[ORM\Table(name: 'Defi_Indice')]
#[ORM\UniqueConstraint(name: 'un_defi_indice_ordre', columns: ['defi_id', 'ordre'])]
class DefiIndice
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Defi::class, inversedBy: 'defiIndices')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Defi $defi;

    #[ORM\Id]
    #[Groups(['defi-read'])]
    #[ORM\ManyToOne(targetEntity: Indice::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Indice $indice;

    #[ORM\Column(type: 'integer')]
    private int $ordre;

    public function __construct(Defi $defi, Indice $indice, int $ordre)
    {
        $this->defi = $defi;
        $this->indice = $indice;
        $this->ordre = $ordre;
    }

    public function getDefi(): Defi
    {
        return $this->defi;
    }

    public function setDefi(Defi $defi): self
    {
        $this->defi = $defi;
        return $this;
    }

    public function getIndice(): Indice
    {
        return $this->indice;
    }

    public function setIndice(Indice $indice): self
    {
        $this->indice = $indice;
        return $this;
    }

    public function getOrdre(): int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;
        return $this;
    }
}
