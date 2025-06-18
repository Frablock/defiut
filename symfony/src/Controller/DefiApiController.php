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

#[Route('/api/defis', name: 'api_defi_')]
class DefiApiController extends AbstractController
{
    public function __construct(
        private readonly DefiRepository $defiRepository,
        private readonly SerializerInterface $serializer,
        private readonly UserRepository $userRepository,
    ) {}


    #[Route('', name: 'list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Return a list of the defis'
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

    #[Route('', name: 'list_by_params', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Return a list of the defis between start_id and start_id + 10 with category, tags, and filter as JSON params in the body'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'start_id', type: 'integer', example: 0),
                new OA\Property(property: 'category', type: 'string', example: 'web'),
                new OA\Property(property: 'tags', type: 'array', items: new OA\Items(type: 'string'), example: ['php', 'symfony']),
                new OA\Property(
                    property: 'filter', 
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'attribute', type: 'string', example: 'difficulte'),
                        new OA\Property(property: 'action', type: 'string', enum: ['asc', 'desc'], example: 'desc')
                    ]
                )
            ]
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




    #[Route('/try_key', name: 'try_key', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Allow the user to try a key for a defi'
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


    #[Route('/get_left_menu_categories', name: 'get_left_menu_categories', methods: ['GET'])]
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

    #[Route('/filter', name: 'filter', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Returns a list of Defi filtered by category and tags'
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
    #[Route('/{id}', name: 'get_single_defi', methods: ['GET'])]
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
