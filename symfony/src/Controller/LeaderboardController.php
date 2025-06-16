<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for managing CTF leaderboard endpoints
 * 
 * Provides REST API endpoints for displaying:
 * - Top 10 participants with their scores
 * - Individual user ranking position
 */
class LeaderboardController extends AbstractController
{
    /**
     * Main endpoint: Get top 10 leaderboard
     * 
     * Returns the top 10 users ranked by total score in descending order.
     * In case of score ties, ranking is determined by creation date (oldest user wins).
     * 
     * @return JsonResponse Array of top 10 users with ranking information
     */
    #[Route('/api/leaderboard', name: 'api_leaderboard', methods: ['GET'])]
    public function getLeaderboard(EntityManagerInterface $em): JsonResponse
    {
        // Get user repository from entity manager
        $userRepository = $em->getRepository(User::class);
        
        // Fetch top 10 users using custom repository method
        $users = $userRepository->findTop10ByScore();
        
        // Build response array with necessary user information
        $leaderboard = [];
        foreach ($users as $index => $user) {
            $leaderboard[] = [
                'ranking' => $index + 1,                    // Position in leaderboard 
                'userId' => $user->getId(),                 // Unique user identifier
                'username' => $user->getUsername(),         // User's display name
                'email' => $user->getMail(),                // User's email address
                'totalScore' => $user->getScoreTotal()      // Total points accumulated in CTF
            ];
        }
        
        // Return JSON response with leaderboard data
        return $this->json($leaderboard);
    }

    /**
     * Endpoint to get specific user ranking
     * 
     * Allows retrieving the exact position of a user in the general ranking,
     * even if they are not in the top 10.
     * 
     * @param int $id User ID to get ranking for
     * @return JsonResponse User information with their ranking or error message
     */
    #[Route('/api/leaderboard/user/{id}', name: 'api_leaderboard_user', methods: ['GET'])]
    public function getUserRanking(int $id, EntityManagerInterface $em): JsonResponse
    {
        // Get user repository from entity manager
        $userRepository = $em->getRepository(User::class);
        
        // Find user by their ID
        $user = $userRepository->find($id);
        
        // Check if user exists in database
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }
        
        // Calculate user's position in general ranking
        $ranking = $userRepository->getUserRanking($user);
        
        // Return user information with their current ranking
        return $this->json([
            'userId' => $user->getId(),                 // Unique user identifier
            'username' => $user->getUsername(),         // User's display name
            'email' => $user->getMail(),                // User's email address
            'ranking' => $ranking,                      // Position in general ranking
            'totalScore' => $user->getScoreTotal()      // Total points accumulated in CTF
        ]);
    }
    #[Route('/api/test-leaderboard', name: 'api_test-leaderboard', methods: ['GET'])]
    public function testLeaderboard(): JsonResponse
{
    // Données JSON de test avec plus de 10 utilisateurs et rankings désordonnés
    $mockData = [
        [
            "ranking" => 15,
            "userId" => 15,
            "username" => "guest",
            "email" => "guest@ctf.com",
            "totalScore" => 800
        ],
        [
            "ranking" => 6,
            "userId" => 11,
            "username" => "reverse_engineer",
            "email" => "kate@ctf.com",
            "totalScore" => 3500
        ],
        [
            "ranking" => 10,
            "userId" => 12,
            "username" => "flag_master",
            "email" => "leo@ctf.com",
            "totalScore" => 4200
        ],
        [
            "ranking" => 1,
            "userId" => 5,
            "username" => "flag_hunter",
            "email" => "eve@ctf.com",
            "totalScore" => 1400
        ],
        [
            "ranking" => 7,
            "userId" => 1,
            "username" => "hacker_pro",
            "email" => "alice@ctf.com",
            "totalScore" => 2500
        ],
        [
            "ranking" => 5,
            "userId" => 4,
            "username" => "web_hacker",
            "email" => "diana@ctf.com",
            "totalScore" => 3200
        ],
        [
            "ranking" => 13,
            "userId" => 13,
            "username" => "root",
            "email" => "root@ctf.com",
            "totalScore" => 1000
        ],
        [
            "ranking" => 3,
            "userId" => 9,
            "username" => "crypto_wizard",
            "email" => "irene@ctf.com",
            "totalScore" => 3600
        ],
        [
            "ranking" => 2,
            "userId" => 2,
            "username" => "cyber_ninja",
            "email" => "bob@ctf.com",
            "totalScore" => 2200
        ],
        [
            "ranking" => 9,
            "userId" => 6,
            "username" => "binary_ninja",
            "email" => "frank@ctf.com",
            "totalScore" => 2800
        ],
        [
            "ranking" => 14,
            "userId" => 14,
            "username" => "admin",
            "email" => "admin@ctf.com",
            "totalScore" => 900
        ],
        [
            "ranking" => 4,
            "userId" => 7,
            "username" => "forensics_expert",
            "email" => "grace@ctf.com",
            "totalScore" => 2500
        ],
        [
            "ranking" => 8,
            "userId" => 8,
            "username" => "pwn_master",
            "email" => "henry@ctf.com",
            "totalScore" => 2200
        ],
        [
            "ranking" => 11,
            "userId" => 10,
            "username" => "exploit_king",
            "email" => "jack@ctf.com",
            "totalScore" => 3800
        ],
        [
            "ranking" => 12,
            "userId" => 3,
            "username" => "code_breaker",
            "email" => "charlie@ctf.com",
            "totalScore" => 1800
        ]
    ];
    
    return $this->json($mockData);
}



}
