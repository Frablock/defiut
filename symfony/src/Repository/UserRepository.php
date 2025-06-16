<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


    /**
     * Find top 10 users by score for leaderboard display
     * 
     * Orders users by total score (descending) and creation date (ascending for ties).
     * Limits results to 10 users maximum.
     * 
     * @return User[] Returns an array of User objects
     */
    public function findTop10ByScore(): array
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.scoreTotal', 'DESC')           // Primary sort: highest score first
            ->addOrderBy('u.creationDate', 'ASC')       // Secondary sort: oldest user wins ties
            ->setMaxResults(10)                         // Limit to top 10 users
            ->getQuery()
            ->getResult();
    }

    /**
     * Calculate user's ranking position in the leaderboard
     * 
     * Counts how many users have a higher score or same score but earlier creation date.
     * Returns the user's position (1-based ranking).
     * 
     * @param User $user The user to calculate ranking for
     * @return int User's position in the ranking (1 = first place)
     */
    public function getUserRanking(User $user): int
    {
        $result = $this->createQueryBuilder('u')
            ->select('COUNT(u.id) + 1 as ranking')      // Count users ahead + 1 for position
            ->where('u.scoreTotal > :userScore')        // Users with higher score
            ->orWhere('u.scoreTotal = :userScore AND u.creationDate < :userCreatedAt') // Same score, earlier date
            ->setParameter('userScore', $user->getScoreTotal())
            ->setParameter('userCreatedAt', $user->getCreationDate())
            ->getQuery()
            ->getSingleScalarResult();
        
        return (int) $result;
    }

    /**
     * Count total number of active users (users with score > 0)
     * 
     * @return int Number of users who have participated in challenges
     */
    public function countActiveUsers(): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.scoreTotal > 0')                 // Only count users who have scored points
            ->getQuery()
            ->getSingleScalarResult();
    }


    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    public function findOneByMail($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.mail = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByToken($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.token = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
