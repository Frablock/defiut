<?php
// src/Controller/DefiApiController.php
namespace App\Controller;

use App\Entity\Defi;
use App\Entity\DefiValidUtilisateur;
use App\Entity\RecentDefi;
use App\Repository\DefiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\UserRepository;
use App\Entity\User;

use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;

class DefiApiController extends AbstractController
{
    public function __construct(
        private readonly DefiRepository $defiRepository,
        private readonly SerializerInterface $serializer,
        private readonly UserRepository $userRepository,
    ) {}


    #[Route('/api/defis', name: 'list', methods: ['GET'])]
    #[OA\Get(
        path: '/api/defis',
        tags: ['Defi'],
        summary: 'List all challenges',
        description: 'Returns a list of all available challenges with their details',
        operationId: 'listDefis',
    )]
    #[OA\Response(
        response: 200,
        description: 'List of challenges retrieved successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                    properties: [
                        new OA\Property(property: 'nom', type: 'string', description: 'Name of the challenge', example: 'Learn PHP'),
                        new OA\Property(property: 'points_recompense', type: 'integer', description: 'Reward points for completing the challenge', example: 100),
                        new OA\Property(property: 'description', type: 'string', description: 'Description of the challenge', example: 'Complete a PHP tutorial'),
                        new OA\Property(property: 'difficulte', type: 'string', description: 'Difficulty level', example: 'beginner'),
                        new OA\Property(property: 'fichier', type: 'array', description: 'Files associated with the challenge', items: new OA\Items()),
                        new OA\Property(property: 'id', type: 'integer', description: 'Challenge ID', example: 1),
                        new OA\Property(property: 'tags', type: 'array', description: 'Tags for the challenge', items: new OA\Items(type: 'string')),
                        new OA\Property(property: 'user', type: 'object', description: 'User who created the challenge')
                    ],
                    type: 'object'
                ))
            ],
            type: 'object'
        )
    )]
    public function list(Request $request): JsonResponse
    {

        // Récupération des défis avec pagination par ID
        $defis = $this->defiRepository->findAll();

        // Sérialisation avec contexte de groupe pour les relations
        $data = $this->serializer->serialize($defis, 'json', [
            'groups' => ['defi-read']
        ]);


        $data = [];

        foreach($defis as $defi) {
            $data[] = [
                'nom' => $defi->getNom(),
                'points_recompense' => $defi->getPointsRecompense(),
                'description' => $defi->getDescription(),
                'difficulte' => $defi->getDifficulte(),
                'fichier' => $defi->getFichiers(),
                'id' => $defi->getId(),
                'tags' => $defi->getTags(),
                'user' => $defi->getUser(),
            ];
        }
        
        return new JsonResponse(['error' => false, 'error_message' => '', 'data' => $data], JsonResponse::HTTP_OK);
    }

    #[Route('/api/defis', name: 'list_by_params', methods: ['POST'])]
    #[OA\Post(
        path: '/api/defis',
        tags: ['Defi'],
        summary: 'List challenges with filters',
        description: 'Returns a filtered and paginated list of challenges based on the provided parameters',
        operationId: 'listDefisByParams',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'start_id', type: 'integer', example: 0, description: 'Starting ID for pagination'),
                    new OA\Property(property: 'category', type: 'string', example: 'web', description: 'Category to filter by'),
                    new OA\Property(property: 'tags', type: 'array', items: new OA\Items(type: 'string'), example: ['php', 'symfony'], description: 'Tags to filter by'),
                    new OA\Property(
                        property: 'filter',
                        type: 'object',
                        description: 'Sorting filter',
                        properties: [
                            new OA\Property(property: 'attribute', type: 'string', example: 'difficulte', description: 'Attribute to sort by'),
                            new OA\Property(property: 'action', type: 'string', enum: ['asc', 'desc'], example: 'desc', description: 'Sort direction')
                        ]
                    )
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Filtered list of challenges retrieved successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', description: 'Challenge ID', example: 1),
                        new OA\Property(property: 'nom', type: 'string', description: 'Name of the challenge', example: 'Learn PHP'),
                        new OA\Property(property: 'points_recompense', type: 'integer', description: 'Reward points', example: 100),
                        new OA\Property(property: 'description', type: 'string', description: 'Challenge description', example: 'Complete a PHP tutorial'),
                        new OA\Property(property: 'difficulte', type: 'string', description: 'Difficulty level', example: 'beginner'),
                        new OA\Property(property: 'categorie', type: 'string', description: 'Category', example: 'web'),
                        new OA\Property(property: 'fichiers', type: 'array', description: 'Files associated with the challenge', items: new OA\Items()),
                        new OA\Property(property: 'tags', type: 'array', description: 'Tags', items: new OA\Items(type: 'string')),
                        new OA\Property(property: 'user', type: 'string', description: 'Username of the creator', example: 'johndoe')
                    ],
                    type: 'object'
                ))
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Invalid filter parameter',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Filter must contain both attribute and action properties')
            ],
            type: 'object'
        )
    )]
    public function listWithCategoryAndTag(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Fixed the parameter extraction (they were all using 'start_id')
        $startId = $data['start_id'] ?? 0;
        $category = $data['category'] ?? null;
        $tags = $data['tags'] ?? null;
        $filter = $data['filter'] ?? null;

        // Validate filter structure if provided
        if ($filter !== null) {
            if (!isset($filter['attribute']) || !isset($filter['action'])) {
                return new JsonResponse([
                    'error' => true, 
                    'error_message' => 'Filter must contain both attribute and action properties'
                ], JsonResponse::HTTP_BAD_REQUEST);
            }
        }

        // Get defis with all filters including sorting
        $defis = $this->defiRepository->findNextDefis($startId, 10, $category, $tags, $filter);

        // Build response data array
        $responseData = [];
        foreach($defis as $defi) {
            $responseData[] = [
                'id' => $defi->getId(),
                'nom' => $defi->getNom(),
                'points_recompense' => $defi->getPointsRecompense(),
                'description' => $defi->getDescription(),
                'difficulte' => $defi->getDifficulte(),
                'categorie' => $defi->getCategorie(), // Added missing category
                'fichiers' => $defi->getFichiers(), // Convert collection to array
                'tags' => $defi->getTags(), // Convert collection to array
                'user' => $defi->getUser()->getUsername() // Adjust based on your User entit
            ];
        }
        
        return new JsonResponse([
            'error' => false, 
            'error_message' => '', 
            'data' => $responseData
        ], JsonResponse::HTTP_OK);
    }




    #[Route('/api/defis/try_key', name: 'try_key', methods: ['POST'])]
    #[OA\Post(
        path: '/api/defis/try_key',
        tags: ['Defi'],
        summary: 'Try a key for a challenge',
        description: 'Allows a user to attempt to validate a challenge by providing its key',
        operationId: 'tryKey',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Challenge ID and key to attempt',
            content: new OA\JsonContent(
                type: 'object',
                required: ['id', 'key'],
                properties: [
                    new OA\Property(property: 'id', type: 'integer', description: 'ID of the challenge to attempt', example: 1),
                    new OA\Property(property: 'key', type: 'string', description: 'Key to attempt for the challenge', example: 'secret123')
                ]
            )
        )
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
        description: 'Key was correct, challenge completed successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'message', type: 'string', example: 'ok')
                ], type: 'object')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request (missing parameters, wrong key, or already completed)',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: [
                    'Missing id or key',
                    'Mauvaise clée',
                    'Le défis est déjà fait'
                ])
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized (missing token or invalid authentication)',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: [
                    'Missing token',
                    'Utilisateur non authentifié'
                ])
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Challenge not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Defi non trouvé')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 429,
        description: 'Too many requests (rate limited)',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Veuillez attendre 1 seconde(s) avant de réessayer')
            ],
            type: 'object'
        )
    )]
    public function try_key(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        // Extract token from Authorization header
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return new JsonResponse(['error' => true, 'error_message' => 'Missing token'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = $this->userRepository->findOneByToken($token);
        
        // Retrieve parameters from the request body (JSON or form data)
        $id = json_decode($request->getContent(), true)['id'] ?? null;
        $key = json_decode($request->getContent(), true)['key'] ?? null;

        if (!$id || !$key) {
            return new JsonResponse(['error' => true, 'error_message' => "Missing id or key"], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Retrieve the Defi by ID
        $defi = $this->defiRepository->find($id);
        if (!$defi) {
            return new JsonResponse(['error' => true, 'error_message' => 'Defi non trouvé'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Get the current user
        if (!$user instanceof User) {
            return new JsonResponse(['error' => true, 'error_message' => 'Utilisateur non authentifié'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Check if the user's last try was less than 2 seconds ago
        $currentTime = new \DateTime();
        $lastTryDate = $user->getLastTryDate();
        
        if ($lastTryDate !== null) {
            $timeDifference = $currentTime->getTimestamp() - $lastTryDate->getTimestamp();
            
            if ($timeDifference < 2) {
                $remainingTime = 2 - $timeDifference;
                return new JsonResponse([
                    'error' => true, 
                    'error_message' => "Veuillez attendre {$remainingTime} seconde(s) avant de réessayer"
                ], JsonResponse::HTTP_TOO_MANY_REQUESTS);
            }
        }

        // Update the user's last try date
        $user->setLastTryDate($currentTime);
        $entityManager->flush();

        // Check if the user has already completed this Defi
        $existingCompletion = $entityManager->getRepository(DefiValidUtilisateur::class)
            ->findOneBy(['user' => $user, 'defi' => $defi]);

        if ($existingCompletion) {
            return new JsonResponse(['error' => true, 'error_message' => 'Le défis est déjà fait'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Verify the provided key
        if ($defi->getKey() === $key) {
            // Increment the user's score
            $user->setScoreTotal($user->getScoreTotal() + $defi->getPointsRecompense()); // Note: using getPointsRecompense()

            // Create a new completion record
            $completion = new DefiValidUtilisateur();
            $completion->setUser($user);
            $completion->setDefi($defi);
            $completion->setDateValid(new \DateTime());
            
            $entityManager->persist($completion);
            $entityManager->flush();

            return new JsonResponse(['error' => false, 'error_message' => '', 'data' => ["message" => "ok"]], JsonResponse::HTTP_OK);
        }
        
        return new JsonResponse(['error' => true, 'error_message' => 'Mauvaise clée'], JsonResponse::HTTP_BAD_REQUEST);
    }


    #[Route('/api/defis/get_left_menu_categories', name: 'get_left_menu_categories', methods: ['GET'])]
    #[OA\Get(
        path: '/api/defis/get_left_menu_categories',
        tags: ['UI', 'Defi'],
        summary: 'Get left menu categories',
        description: 'Returns a list of categories for the left sidebar menu',
        operationId: 'getLeftMenuCategories',
    )]
    #[OA\Response(
        response: 200,
        description: 'Menu categories retrieved successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                    properties: [
                        new OA\Property(property: 'title', type: 'string', description: 'Category title', example: 'Tout les défis'),
                        new OA\Property(property: 'img', type: 'string', description: 'Icon class for the category', example: 'bi bi-collection'),
                        new OA\Property(property: 'url', type: 'string', description: 'URL for the category', example: '/all')
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
                new OA\Property(property: 'error_message', type: 'string', example: 'Une erreur s\'est produite dans le menu des catégories rapide.'),
                new OA\Property(property: 'data', type: 'string', example: null, nullable: true)
            ],
            type: 'object'
        )
    )]
    public function getLeftMenuCategories(Request $request): JsonResponse
    {
        try {
            // Menu categories for the left sidebard
            $categories = [
                
            ["title" => "Tout les défis",           "img" => "bi bi-collection",           "url" => "/all"],
            ["title" => "Algorithmie",              "img" => "bi bi-diagram-3",             "url" => "/algorithmie"],
            ["title" => "Web",                      "img" => "bi bi-code-slash",                 "url" => "/web"],
            ["title" => "Base de donnée",           "img" => "bi bi-database",              "url" => "/base_de_donnee"],
            ["title" => "Rétroingénierie",          "img" => "bi bi-arrow-repeat",          "url" => "/retroingenierie"],
            ["title" => "Stéganographie",           "img" => "bi bi-eye-slash",             "url" => "/stegonographie"],
            ["title" => "Collaboration",            "img" => "bi bi-people",                "url" => "/collaboration"],
        ];


            return new JsonResponse([
                'error' => false,
                'data' => $categories,
                'error_message' => ''
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'error' => true,
                'data' => null,
                'error_message' => "Une erreur s'est produite dans le menu des catégories rapide."
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/defis/filter', name: 'filter', methods: ['POST'])]
    #[OA\Post(
        path: '/api/defis/filter',
        tags: ['Defi'],
        summary: 'Filter challenges by category and tags',
        description: 'Returns a filtered list of challenges based on specified category and tags',
        operationId: 'filterDefisByCategoryAndTags',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Filter criteria for challenges',
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'tags', type: 'array', items: new OA\Items(type: 'string'), description: 'Tags to filter by', example: ['php', 'database']),
                    new OA\Property(property: 'category', type: 'string', description: 'Category to filter by', example: 'web')
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Filtered challenges retrieved successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', description: 'Challenge ID', example: 1),
                        new OA\Property(property: 'nom', type: 'string', description: 'Name of the challenge', example: 'Learn PHP'),
                        new OA\Property(property: 'points_recompense', type: 'integer', description: 'Reward points', example: 100),
                        new OA\Property(property: 'description', type: 'string', description: 'Challenge description', example: 'Complete a PHP tutorial'),
                        new OA\Property(property: 'difficulte', type: 'string', description: 'Difficulty level', example: 'beginner'),
                        new OA\Property(property: 'categorie', type: 'string', description: 'Category', example: 'web'),
                        new OA\Property(property: 'fichiers', type: 'array', description: 'Files associated with the challenge', items: new OA\Items()),
                        new OA\Property(property: 'tags', type: 'array', description: 'Tags', items: new OA\Items(type: 'string')),
                        new OA\Property(property: 'user', type: 'object', description: 'User who created the challenge')
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
                new OA\Property(property: 'error_message', type: 'string', example: 'An error occurred while filtering Defi by category and tags.'),
                new OA\Property(property: 'data', type: 'string', example: null, nullable: true)
            ],
            type: 'object'
        )
    )]
    public function filterDefisByCategoryAndTags(Request $request): JsonResponse
    {
        try {
            // Extract the JSON body from the request
            $body = json_decode($request->getContent(), true);
            $tags = $body['tags'] ?? [];
            $category = $body['category'] ?? null;

            // Fetch Defi filtered by category and tags
            $defis = $this->defiRepository->findByCategoryAndTags($category, $tags);

            // Serialize the result with the appropriate group
            $data = $this->serializer->serialize($defis, 'json', ['groups' => ['defi-read']]);

            // Return a standardized JSON response
            return new JsonResponse([
                'error' => false,
                'data' => json_decode($data),
                'error_message' => ''
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'error' => true,
                'data' => null,
                'error_message' => "An error occurred while filtering Defi by category and tags."
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    //Il faut placer cette fonction a la toute fin de cette classe, sinon les requêtes vont croire que les routes appelées sont des ID et vont venir ici
    #[Route('/api/defis/{id}', name: 'get_single_defi', methods: ['GET'])]
    #[OA\Get(
        path: '/api/defis/{id}',
        tags: ['Defi'],
        summary: 'Get a single challenge by ID',
        description: 'Returns details for a specific challenge. If authenticated, adds the challenge to the user\'s recent challenges list.',
        operationId: 'getSingleDefi',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID of the challenge to retrieve',
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'Authorization',
                in: 'header',
                required: false,
                description: 'Optional bearer token for authentication. If provided, the challenge will be added to the user\'s recent challenges list.',
                schema: new OA\Schema(type: 'string')
            )
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'Challenge retrieved successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'id', type: 'integer', description: 'Challenge ID', example: 1),
                    new OA\Property(property: 'nom', type: 'string', description: 'Name of the challenge', example: 'Learn PHP'),
                    new OA\Property(property: 'points_recompense', type: 'integer', description: 'Reward points', example: 100),
                    new OA\Property(property: 'description', type: 'string', description: 'Challenge description', example: 'Complete a PHP tutorial'),
                    new OA\Property(property: 'difficulte', type: 'string', description: 'Difficulty level', example: 'beginner'),
                    new OA\Property(property: 'categorie', type: 'string', description: 'Category', example: 'web'),
                    new OA\Property(property: 'fichiers', type: 'array', description: 'Files associated with the challenge', items: new OA\Items()),
                    new OA\Property(property: 'tags', type: 'array', description: 'Tags', items: new OA\Items(type: 'string')),
                    new OA\Property(property: 'user', type: 'object', description: 'User who created the challenge')
                ], type: 'object')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Challenge not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Défis introuvable')
            ],
            type: 'object'
        )
    )]
    public function getSingleDefi(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $defi = $this->defiRepository->find($id);

        if (!$defi) {
            return new JsonResponse(['error' => true, 'error_message' => "Défis introuvable"], JsonResponse::HTTP_NOT_FOUND);
        }

        $token = $request->headers->get('Authorization');
        if ($token) {
            $user = $this->userRepository->findOneByToken($token);
            if ($user instanceof User) {

                $recentDefisRepository = $em->getRepository(RecentDefi::class);
                
                // Check if the user already has this defi in recent defis
                $existingRecentDefi = $recentDefisRepository->findOneBy([
                    'user' => $user,
                    'defi' => $defi
                ]);

                // Only add if it doesn't exist
                if (!$existingRecentDefi) {
                    $recentDefis = new RecentDefi($user, $defi, new \DateTime());
                    $user->addRecentDefi($recentDefis);
                    
                    // Persist the new RecentDefi
                    $em->persist($recentDefis);
                    $em->flush();
                } else {
                    // Update the access date if it already exists
                    $existingRecentDefi->setDateAcces(new \DateTime());
                    $em->flush();
                }
            }
        }

        $data = $this->serializer->serialize($defi, 'json', ['groups' => ['defi-read']]);
        return new JsonResponse(['error' => false, 'data' => json_decode($data)], JsonResponse::HTTP_OK);
    }
}
