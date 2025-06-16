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
        try {
            // Get user repository from entity manager
            $userRepository = $em->getRepository(User::class);
            
            // Fetch top 10 users using custom repository method
            $users = $userRepository->findTop10ByScore();
            
            // Build response array with necessary user information
            $leaderboard = [];
            foreach ($users as $user) {
                $leaderboard[] = [
                    'username' => $user->getUsername(),         // User's display name
                    'total_score' => $user->getScoreTotal()      // Total points accumulated in CTF
                ];
            }
            
            
            return $this->json([
                'error' => false,
                'data' => $leaderboard,
                'error_message' => ''
            ]);
        } catch (\Throwable $e) {
            
            return $this->json([
                'error' => true,
                'data' => null,
                'error_message' => "Une erreur s'est produite dans le LeaderBoard."
            ], 500);
        }
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
        try {
            // Get user repository from entity manager
            $userRepository = $em->getRepository(User::class);
            
            // Find user by their ID
            $user = $userRepository->find($id);
            
            // Check if user exists in database
            if (!$user) {
                return $this->json([
                    'error' => true,
                    'data' => null,
                    'error_message' => 'User not found'
                ], 404);
            }
            
            // Calculate user's position in general ranking
            $ranking = $userRepository->getUserRanking($user);
            
            // Return standardized JSON response with user ranking data
            return $this->json([
                'error' => false,
                'data' => [
                    'userId' => $user->getId(),                 // Unique user identifier
                    'username' => $user->getUsername(),         // User's display name
                    'email' => $user->getMail(),                // User's email address
                    'ranking' => $ranking,                      // Position in general ranking
                    'totalScore' => $user->getScoreTotal()      // Total points accumulated in CTF
                ],
                'error_message' => ''
            ]);
        } catch (\Throwable $e) {
            // Return standardized JSON response on error
            return $this->json([
                'error' => true,
                'data' => null,
                'error_message' => "Une erreur s'est produite dans le LeaderBoard."
            ], 500);    
        }
    }
}
