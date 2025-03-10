<?php
// src/Entity/DefiIndice.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Repository\DefiIndiceRepository')]
#[ORM\Table(name: 'defi_indice')]
#[ORM\UniqueConstraint(name: 'un_defi_indice_ordre', columns: ['defi_id', 'ordre'])]
class DefiIndice
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Defi::class, inversedBy: 'defiIndices')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Defi $defi;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Indice::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Indice $indice;

    #[ORM\Column(type: 'integer')]
    private int $ordre;

    // Getters/setters...
}
