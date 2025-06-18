<?php

namespace App\Repository;
use App\Entity\User;  
use App\Entity\DefiValidUtilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DefiValidUtilisateur>
 */
class DefiValidUtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DefiValidUtilisateur::class);
    }

    public function findValidatedDefisByUser(User $user): array
    {
        return $this->createQueryBuilder('dv')
            ->select('d.nom   AS nom')
            ->addSelect('d.pointsRecompense AS points')
            ->addSelect('dv.dateValid    AS dateValid')
            ->innerJoin('dv.defiId', 'd')    // collection de Defi
            ->innerJoin('dv.userId', 'u')    // collection de User
            ->andWhere('u = :user')
            ->setParameter('user', $user)
            ->orderBy('dv.dateValid', 'DESC')
            ->getQuery()
            ->getArrayResult()
        ;
    }
    //    /**
    //     * @return DefiValidUtilisateur[] Returns an array of DefiValidUtilisateur objects
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

    //    public function findOneBySomeField($value): ?DefiValidUtilisateur
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
