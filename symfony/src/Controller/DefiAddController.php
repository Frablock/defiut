<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

use App\Repository\UserRepository;
use App\Entity\User;
use App\Repository\DefiRepository;
use App\Entity\Defi;

use OpenApi\Attributes as OA;

final class DefiAddController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}


    #[Route('/api/defi_add', name: 'app_defi_add')]
    #[OA\Post(
        path: '/api/defi_add',
        tags: ['Defi'],
        summary: 'Add a new challenge',
        description: 'Creates a new challenge. Requires editor role.',
        operationId: 'addDefi',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Challenge details to create',
            content: new OA\JsonContent(
                required: ['nom', 'desc', 'diff', 'key', 'score'],
                properties: [
                    new OA\Property(property: 'nom', type: 'string', description: 'Name of the challenge', example: 'PHP Basics'),
                    new OA\Property(property: 'desc', type: 'string', description: 'Description of the challenge', example: 'A challenge to test basic PHP knowledge'),
                    new OA\Property(property: 'diff', type: 'string', description: 'Difficulty level', example: 'beginner'),
                    new OA\Property(property: 'key', type: 'string', description: 'Solution key for the challenge', example: 'secret123'),
                    new OA\Property(property: 'score', type: 'integer', description: 'Points awarded for completing the challenge', example: 100)
                ],
                type: 'object'
            )
        ),
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'Authorization',
        in: 'header',
        required: true,
        description: 'Bearer token for authentication',
        schema: new OA\Schema(type: 'string', example: 'Bearer abc123.def456...')
    )]
    #[OA\Response(
        response: 200,
        description: 'Challenge created successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', type: 'object', example: [], description: 'Empty object on success')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Missing data in request body',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Missing data')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized (missing/invalid token or missing required role)',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: [
                    'Missing token',
                    'Invalid credentials',
                    'Missing permission'
                ])
            ],
            type: 'object'
        )
    )]
    public function add_defi(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return new JsonResponse(['error' => true, 'error_message' => 'Missing token'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = $this->userRepository->findOneByToken($token);
        if (!$user instanceof User) {
            throw new AuthenticationException('Invalid credentials');
        }

        if (!in_array("editor", $user->getRoles())) {
            return new JsonResponse(['error' => true, 'error_message' => 'Missing permission'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Extract credentials from request
        $data = json_decode($request->getContent(), true);

        $nom = $data['nom'] ?? null;
        $desc = $data['desc'] ?? null;
        $diff = $data['diff'] ?? null;
        $key = $data['key'] ?? null;
        $score = $data['score'] ?? null;

        if (!$nom || !$desc || !$diff || !$key || !$score) {
            return new JsonResponse(['error' => true, 'error_message' => 'Missing data'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $defi = new Defi();
        /*
        foreach ($tags as $tag) {
            $defi->addTag($tag);
        }
        foreach ($hints as $hint) {
            $defi->addDefiIndice($hint);
        }
        */
        $defi->setNom($nom);
        $defi->setDescription($desc);
        $defi->setDifficulte($diff);
        $defi->setKey($key);
        $defi->setScore($score);
        $entityManager->persist($defi);
        $entityManager->flush();
        return new JsonResponse(['error' => false, 'error_message' => '', 'data' => []], JsonResponse::HTTP_OK);
    }
}
