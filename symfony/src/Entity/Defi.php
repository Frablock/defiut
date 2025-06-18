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
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'defis')]
    #[ORM\JoinTable(name: 'Defi_Tag')]
    private Collection $tags;

    #[Groups(['defi-read'])]
    #[ORM\OneToMany(mappedBy: 'defi', targetEntity: DefiIndice::class, cascade: ['persist', 'remove'])]
    private Collection $defiIndices;

    #[Groups(['defi-read'])]
    #[ORM\ManyToMany(targetEntity: Fichier::class, inversedBy: 'defis')]
    #[ORM\JoinTable(name: 'Defi_Fichier')]
    private Collection $fichiers;

    #[ORM\OneToMany(mappedBy: 'defi', targetEntity: RecentDefi::class, cascade: ['persist', 'remove'])]
    private Collection $recentDefis;

    #[ORM\ManyToOne(inversedBy: 'defiId')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DefiValidUtilisateur $defiValidUtilisateur = null;

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

    public function setNom(string $nom)
    {
        $this->nom = $nom;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $desc)
    {
        $this->description = $desc;
    }

    public function getPointsRecompense(): ?int
    {
        return $this->pointsRecompense;
    }

    public function getDifficulte(): ?int
    {
        return $this->difficulte;
    }

    public function setDifficulte(int $diff)
    {
        $this->difficulte = $diff;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getFichiers(): ?Collection
    {
        return $this->fichiers;
    }

    public function addFichier(Fichier $fichier): self
    {
        if (!$this->fichiers->contains($fichier)) {
            $this->fichiers->add($fichier);
        }
        return $this;
    }

    public function removeFichier(Fichier $fichier): self
    {
        $this->fichiers->removeElement($fichier);
        return $this;
    }

    public function getTags(): array
    {
        $tagsArray = $this->tags->toArray();
        $tagNames = array_map(function($tag) {
            return $tag->getNom();
        }, $tagsArray);
        return $tagNames;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);
        return $this;
    }
    
    public function getDefiIndices(): Collection
    {
        return $this->defiIndices;
    }

    public function addDefiIndice(DefiIndice $defiIndice): self
    {
        if (!$this->defiIndices->contains($defiIndice)) {
            $this->defiIndices->add($defiIndice);
            $defiIndice->setDefi($this);
        }
        return $this;
    }

    public function removeDefiIndice(DefiIndice $defiIndice): self
    {
        if ($this->defiIndices->removeElement($defiIndice)) {
            if ($defiIndice->getDefi() === $this) {
                $defiIndice->setDefi(null);
            }
        }
        return $this;
    }

    public function getRecentDefis(): Collection
    {
        return $this->recentDefis;
    }

    public function addRecentDefi(RecentDefi $recentDefi): self
    {
        if (!$this->recentDefis->contains($recentDefi)) {
            $this->recentDefis->add($recentDefi);
            $recentDefi->setDefi($this);
        }
        return $this;
    }

    public function removeRecentDefi(RecentDefi $recentDefi): self
    {
        if ($this->recentDefis->removeElement($recentDefi)) {
            if ($recentDefi->getDefi() === $this) {
                $recentDefi->setDefi(null);
            }
        }
        return $this;
    }

    public function getKey(): string
    {
        return $this->cle;
    }

    public function setKey(string $key)
    {
        $this->cle = $key;
    }

    public function getScore(): int
    {
        return $this->pointsRecompense;
    }

    public function setScore(int $score)
    {
        $this->pointsRecompense = $score;
    }

<<<<<<< HEAD
    public function getDefiValidUtilisateur(): ?DefiValidUtilisateur
    {
        return $this->defiValidUtilisateur;
    }

    public function setDefiValidUtilisateur(?DefiValidUtilisateur $defiValidUtilisateur): static
    {
        $this->defiValidUtilisateur = $defiValidUtilisateur;

        return $this;
=======
    public function getCategorie(): string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie)
    {
        $this->categorie = $categorie;
>>>>>>> 67dd69df1d4c42ed3b8ce97693e2b41fc886b29f
    }
}