<?php
namespace App\Controller;


use App\Entity\DefiValidUtilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Exception;

use OpenApi\Attributes as OA;

class InfoUserController extends AbstractController
{

    #[Route('/api/get_info_user', name: 'get_info_user', methods: ['GET'])]
    #[OA\Get(
        path: '/api/get_info_user',
        tags: ['User'],
        summary: 'Get user information',
        description: 'Returns user information including score, profile details, and validated challenges. If no token is provided, returns empty data fields.',
        operationId: 'getInfoUser',
        parameters: [
            new OA\Parameter(
                name: 'Authorization',
                in: 'header',
                required: false,
                description: 'Optional bearer token for authentication. If provided, returns information for the authenticated user.',
                schema: new OA\Schema(type: 'string')
            )
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'User information retrieved successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'scoreGlobal', type: 'integer', nullable: true, description: 'User\'s total score', example: 1500),
                    new OA\Property(property: 'pseudo', type: 'string', nullable: true, description: 'User\'s username', example: 'johndoe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', nullable: true, description: 'User\'s email address', example: 'john@example.com'),
                    new OA\Property(property: 'creationCompte', type: 'string', format: 'date-time', nullable: true, description: 'Account creation date', example: '2023-01-01T12:00:00Z'),
                    new OA\Property(property: 'lastConnection', type: 'string', format: 'date-time', nullable: true, description: 'Date of last connection', example: '2023-06-15T09:30:00Z'),
                    new OA\Property(property: 'defis_valide', type: 'array', items: new OA\Items(type: 'object'), nullable: true, description: 'Array of validated challenges')
                ], type: 'object')
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
                new OA\Property(property: 'error_message', type: 'string', example: 'Une erreur s\'est produite dans le InfoUserController.'),
                new OA\Property(property: 'data', type: 'string', example: null, nullable: true)
            ],
            type: 'object'
        )
    )]
    public function getInfoUser(EntityManagerInterface $em, Request $request): JsonResponse
    {
        try {
            $DefisValidesArray = [];
            $token = $request->headers->get('Authorization');
            if ($token) {
                $user = $em->getRepository(User::class)->findOneByToken($token);
                $DefisValidesArray = $em->getRepository(DefiValidUtilisateur::class)->findValidatedDefisByUser($user);
                if ($user instanceof User) {
                    $globalScore = $user->getScoreTotal();
                    $pseudo = $user->getUsername();
                    $email = $user->getMail();
                    $creationCompte = $user->getCreationDate();
                    $lastConnection = $user->getLastCo();
                }
            }
            return new JsonResponse(
                [
                    'error'=> false,
                    'data'=> [
                        'scoreGlobal' => $globalScore,
                        'pseudo' => $pseudo,
                        'email' => $email,
                        'creationCompte' => $creationCompte,
                        'lastConnection' => $lastConnection,
                        'defis_valide' => $DefisValidesArray 
                    ],
                ]
            );
        } catch (\Throwable $e) {
                
            return $this->json([
                'error' => true,
                'data' => null,
                'error_message' => "Une erreur s'est produite dans le InfoUserController."
            ], 500);
        }
    }
}