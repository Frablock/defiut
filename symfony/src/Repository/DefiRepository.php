<?php

namespace App\Repository;

use App\Entity\Defi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Defi>
 */
class DefiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Defi::class);
    }

    public function findNextDefis(int $startId, int $limit): array
    {
        return $this->createQueryBuilder('d')
        ->andWhere('d.id >= :id')
        ->setParameter('id', $startId)
        ->orderBy('d.id', 'ASC')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
    }

    public function findAll(): array
    {
        return $this->findBy(array(), array('nom' => 'ASC'));
    }

    /**
     * Returns an array of Defi objects filtered by category and tags.
     *
     * @param string|null $category The category name to filter by, or null for any category
     * @param string[] $tags List of tag names to filter by
     * @return Defi[] The filtered Defi objects
     */
    public function findByCategoryAndTags(?string $category, array $tags): array
    {
        $qb = $this->createQueryBuilder('d')
            ->leftJoin('d.tags', 't')
            ->addSelect('t');

        if ($category !== null) {
            $qb->andWhere('d.categorie = :category')
            ->setParameter('category', $category);
        }

        if (!empty($tags)) {
            $qb->andWhere('t.nom IN (:tags)')
            ->setParameter('tags', $tags);
        }

        return $qb->getQuery()->getResult();
    }



    //    /**
    //     * @return Defi[] Returns an array of Defi objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Defi
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
