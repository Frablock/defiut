<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

use OpenApi\Attributes as OA;

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
     * Get top 10 leaderboard
     * 
     * Returns the top 10 users ranked by total score in descending order.
     * In case of score ties, ranking is determined by creation date (oldest user wins).
     * 
     * @return JsonResponse Array of top 10 users with ranking information
     */
    #[Route('/api/leaderboard', name: 'api_leaderboard', methods: ['GET'])]
    #[OA\Get(
        path: '/api/leaderboard',
        tags: ['Leaderboard'],
        summary: 'Get top 10 leaderboard',
        description: 'Returns the top 10 users ranked by total score in descending order. In case of score ties, ranking is determined by creation date (oldest user wins).',
        operationId: 'getLeaderboard',
    )]
    #[OA\Response(
        response: 200,
        description: 'Top 10 leaderboard retrieved successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                    properties: [
                        new OA\Property(property: 'username', type: 'string', description: 'User\'s display name', example: 'johndoe'),
                        new OA\Property(property: 'total_score', type: 'integer', description: 'Total points accumulated in CTF', example: 1500)
                    ],
                    type: 'object'
                ))
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Internal server error',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Une erreur s\'est produite dans le LeaderBoard.'),
                new OA\Property(property: 'data', type: 'string', example: null, nullable: true)
            ],
            type: 'object'
        )
    )]
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
    #[OA\Get(
        path: '/api/leaderboard/user/{id}',
        tags: ['Leaderboard'],
        summary: 'Get specific user ranking',
        description: 'Retrieves the exact position of a user in the general ranking, even if they are not in the top 10.',
        operationId: 'getUserRanking',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'User ID to get ranking for',
                schema: new OA\Schema(type: 'integer')
            )
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'User ranking retrieved successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'userId', type: 'integer', description: 'Unique user identifier', example: 1),
                    new OA\Property(property: 'username', type: 'string', description: 'User\'s display name', example: 'johndoe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', description: 'User email address', example: 'john@example.com'),
                    new OA\Property(property: 'ranking', type: 'integer', description: 'Position in general ranking', example: 5),
                    new OA\Property(property: 'totalScore', type: 'integer', description: 'Total points accumulated in CTF', example: 1200)
                ], type: 'object')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'User not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'User not found'),
                new OA\Property(property: 'data', type: 'string', example: null, nullable: true)
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Internal server error',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Une erreur s\'est produite dans le LeaderBoard.'),
                new OA\Property(property: 'data', type: 'string', example: null, nullable: true)
            ],
            type: 'object'
        )
    )]
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
