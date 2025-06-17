<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function getTop5Tags(): array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.defis', 'd')
            ->groupBy('t.id')
            ->orderBy('COUNT(d.id)', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }
}
