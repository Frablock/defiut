<?php

// src/Entity/RecentDefi.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Repository\RecentDefiRepository')]
#[ORM\Table(name: 'Defi_Utilisateur_Recents')]
#[ORM\Index(name: 'idx_date_acces', columns: ['date_acces'])]
class RecentDefi
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'recentDefis')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Defi::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Defi $defi;

    #[ORM\Column(name: 'date_acces', type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeInterface $dateAcces;

    public function getDateAcces(): \DateTimeInterface
    {
        return $this->dateAcces;
    }

    public function getDefi(): Defi
    {
        return $this->defi;
    }

    public function getUser(): User
    {
        return $this->user;
    }
    // Getters/setters...
}
