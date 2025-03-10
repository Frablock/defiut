<?php
// src/Entity/Defi.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: 'App\Repository\DefiRepository')]
#[ORM\Table(name: 'defi')]
#[ORM\UniqueConstraint(name: 'un_defi_cle', fields: ['cle'])]
class Defi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $nom;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $cle;

    #[ORM\Column(type: 'integer')]
    private int $pointsRecompense;

    #[ORM\Column(type: 'string', length: 255)]
    private string $categorie;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Range(min: 1, max: 5)]
    private ?int $difficulte = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'defis')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[ORM\JoinTable(name: 'defi_tag')]
    private Collection $tags;

    #[ORM\OneToMany(mappedBy: 'defi', targetEntity: DefiIndice::class, orphanRemoval: true)]
    private Collection $defiIndices;

    #[ORM\ManyToMany(targetEntity: Fichier::class)]
    #[ORM\JoinTable(name: 'defi_fichier')]
    private Collection $fichiers;

    #[ORM\OneToMany(mappedBy: 'defi', targetEntity: RecentDefi::class)]
    private Collection $recentDefis;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->defiIndices = new ArrayCollection();
        $this->fichiers = new ArrayCollection();
        $this->recentDefis = new ArrayCollection();
    }

    // Getters/setters...
}
