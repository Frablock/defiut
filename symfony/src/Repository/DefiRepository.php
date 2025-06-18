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

    public function findNextDefis(int $startId, int $limit, ?string $category = null, ?array $tags = null, ?array $filter = null): array
    {
        $qb = $this->createQueryBuilder('d')
            ->andWhere('d.id >= :id')
            ->setParameter('id', $startId)
            ->setMaxResults($limit);

        // Filter by category if provided
        if ($category !== null && $category !== '') {
            $qb->andWhere('d.categorie = :category')
            ->setParameter('category', $category);
        }

        // Filter by tags if provided
        if ($tags !== null && !empty($tags)) {
            $qb->leftJoin('d.tags', 't')
            ->andWhere('t.nom IN (:tagNames)')
            ->setParameter('tagNames', $tags)
            ->groupBy('d.id')
            ->having('COUNT(DISTINCT t.id) = :tagCount')
            ->setParameter('tagCount', count($tags));
        }

        // Handle sorting filter
        if ($filter !== null && isset($filter['attribute']) && isset($filter['action'])) {
            $attribute = $filter['attribute'];
            $action = strtoupper($filter['action']);
            
            // Validate action
            if (!in_array($action, ['ASC', 'DESC'])) {
                $action = 'ASC'; // Default fallback
            }
            
            // Map frontend attribute names to entity properties
            $allowedAttributes = [
                'nom' => 'd.nom',
                'points_recompense' => 'd.pointsRecompense',
                'difficulte' => 'd.difficulte',
                'categorie' => 'd.categorie',
                'id' => 'd.id'
            ];
            
            if (array_key_exists($attribute, $allowedAttributes)) {
                // Clear any existing orderBy and set new one
                $qb->orderBy($allowedAttributes[$attribute], $action);
            } else {
                // Default sorting if invalid attribute
                $qb->orderBy('d.id', 'ASC');
            }
        } else {
            // Default sorting when no filter provided
            $qb->orderBy('d.id', 'ASC');
        }

        return $qb->getQuery()->getResult();
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
