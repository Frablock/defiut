<?php
// src/Entity/Defi.php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: 'App\Repository\DefiRepository')]
#[ORM\Table(name: 'Defi')]
#[ORM\UniqueConstraint(name: 'id', fields: ['id'])]
class Defi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Groups(['defi-read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private string $nom;

    #[Groups(['defi-read'])]
    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $cle;

    #[Groups(['defi-read'])]
    #[ORM\Column(type: 'integer')]
    private int $pointsRecompense;

    #[ORM\Column(type: 'string', length: 255)]
    private string $categorie;

    #[Groups(['defi-read'])]
    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Range(min: 1, max: 5)]
    private ?int $difficulte = null;

    #[Groups(['defi-read'])]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'defis')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[Groups(['defi-read'])]
    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[ORM\JoinTable(name: 'Defi_Tag')]
    private Collection $tags;

    #[Groups(['defi-read'])]
    #[ORM\ManyToMany(targetEntity: Indice::class)]
    #[ORM\JoinTable(name: 'Defi_Indice')]
    private Collection $defiIndices;

    #[Groups(['defi-read'])]
    #[ORM\ManyToMany(targetEntity: Fichier::class)]
    #[ORM\JoinTable(name: 'Defi_Fichier')]
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

    public function __toString()
    {
        return $this->nom." ".$this->description;
    }

    public function getId() : int
    {
        return $this->id;
    }
    
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPointsRecompense(): ?int
    {
        return $this->pointsRecompense;
    }

    public function getDifficulte(): ?int
    {
        return $this->difficulte;
    }

    public function getUser(): ?string
    {
        return $this->user->getUsername();
    }

    public function getFichiers(): ?Collection
    {
        return $this->fichiers;
    }

    public function getTags(): array#Collection
    {
        //return $this->tags;
        $tagsArray = $this->tags->toArray();

        // Use array_map to transform the array
        $tagNames = array_map(function($tag) {
            return $tag->getNom();
        }, $tagsArray);

        return $tagNames;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addDefi($this);
        }
        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeDefi($this);
        }
        return $this;
    }
    
    public function getDefiIndices(): ?Collection
    {
        return $this->defiIndices;
    }

    public function getKey(): string
    {
        return $this->cle;
    }

    public function getScore(): string
    {
        return $this->pointsRecompense;
    }
}
