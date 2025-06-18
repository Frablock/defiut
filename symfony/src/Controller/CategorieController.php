<?php
namespace App\Controller;

use App\Entity\Defi;
use App\Entity\RecentDefi;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Exception;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

use OpenApi\Attributes as OA;

/**
 * Controller to serve the lobby categories endpoint
 *
 * Provides the top tags and the recent challenges of an authenticated user.
 */
class CategorieController extends AbstractController
{
    /**
     * GET /api/defis/get_lobby_categories
     *
     * Returns:
     * - top_defis: array of top 5 defis with most validations in the last 48h
     * - defis_recents: array of 5 most recent challenges for the user (title + id), empty if not authenticated
     *
     * @param EntityManagerInterface $em   Doctrine entity manager
     * @param Request                $request HTTP request to access headers
     * @return JsonResponse Structured JSON response with error flag and data
     */
    #[Route('/api/defis/get_lobby_categories', name: 'get_lobby_categories', methods: ['GET'])]
    #[OA\Get(
        path: '/api/defis/get_lobby_categories',
        tags: ['Defi'],
        summary: 'Get top defis and recent challenges',
        description: 'Returns top 5 defis with most validations in the last 48 hours and up to 5 most recent challenges for the authenticated user. If not authenticated, returns only top defis.',
        operationId: 'getLobbyCategories',
        parameters: [
            new OA\Parameter(
                name: 'Authorization',
                in: 'header',
                required: false,
                description: 'Optional bearer token for authentication. If provided, includes recent challenges for the user.',
                schema: new OA\Schema(type: 'string', example: 'Bearer abc123.def456...')
            )
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'Top defis and recent challenges retrieved successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'top_defis', type: 'array', items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', description: 'Defi ID', example: 123),
                            new OA\Property(property: 'title', type: 'string', description: 'Defi name', example: 'SQL Injection Challenge'),
                            new OA\Property(property: 'validation_count', type: 'integer', description: 'Number of validations in the last 48h', example: 42),
                            new OA\Property(property: 'difficulty', type: 'integer', description: 'Difficulty level (1-5)', example: 3),
                            new OA\Property(property: 'points', type: 'integer', description: 'Reward points', example: 100)
                        ],
                        type: 'object'
                    ), description: 'Array of top 5 defis with most validations in the last 48 hours'),
                    new OA\Property(property: 'defis_recents', type: 'array', items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'title', type: 'string', description: 'Challenge name', example: 'PHP Basics'),
                            new OA\Property(property: 'id', type: 'integer', description: 'Challenge ID', example: 123)
                        ],
                        type: 'object'
                    ), description: 'Array of up to 5 most recent challenges for the user. Empty if not authenticated.')
                ], type: 'object')
            ],
            type: 'object'
        )
    )]
    public function getCategories(EntityManagerInterface $em, Request $request): JsonResponse
    {
        $defiRepository = $em->getRepository(Defi::class);
        
        // Get top 5 defis based on validations in the last 48 hours
        // Replace the private method call with:
        $topDefis = $defiRepository->getTop5DefisByValidations();

        $topDefisArray = [];
        $recentDefisArray = [];
        
        // Build list of top defis for the response
        foreach ($topDefis as $defiData) {
            $defi = $defiData['defi'];
            $topDefisArray[] = [
                'id' => $defi->getId(),
                'title' => $defi->getNom(),
                'validation_count' => $defiData['validation_count'],
                'difficulty' => $defi->getDifficulte(),
                'points' => $defi->getPointsRecompense()
            ];
        }

        $token = $request->headers->get('Authorization');
        if ($token) {
            // Find the User by their stored token
            $user = $em->getRepository(User::class)->findOneByToken($token);
            if ($user instanceof User) {
                // Get the 5 most recent défis for the user, ordered by date (latest first)
                $recentDefisRepository = $em->getRepository(RecentDefi::class);
                $recentDefis = $recentDefisRepository->findBy(
                    ['user' => $user],           // criteria
                    ['dateAcces' => 'DESC'],     // order by date descending (latest first)
                    5                            // limit to 5 results
                );
                
                foreach ($recentDefis as $recentDefi) {
                    $recentDefisArray[] = [
                        'title' => $recentDefi->getDefi()->getNom(),
                        'id' => $recentDefi->getDefi()->getId()
                    ];
                }
            }
        }
        
        return new JsonResponse(
            [
                'error' => false,
                'data' => [
                    'top_defis' => $topDefisArray,           // Top 5 defis by validations in last 48h
                    'defis_recents' => $recentDefisArray     // 5 most recent challenges for the user
                ],
            ]
        );
    }
}