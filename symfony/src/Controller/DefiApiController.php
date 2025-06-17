<?php
// src/Controller/DefiApiController.php
namespace App\Controller;

use App\Entity\Defi;
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
        description: 'Return a list of the defis between start_id and start_id + 10'
    )]
    public function list(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $startId = $data['start_id'] ?? 0;

        // Récupération des défis avec pagination par ID
        $defis = $this->defiRepository->findNextDefis($startId, 10);

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
                'fichier' => $defi->getDifficulte(),
                'id' => $defi->getId(),
                'tags' => $defi->getTags(),
                'user' => $defi->getUser(),
            ];
        }
        
        return new JsonResponse(['error' => false, 'error_message' => '', 'data' => $data], JsonResponse::HTTP_OK);
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
            return new JsonResponse(['error' => true, 'error_message' => 'Defi not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Get the current user
        if (!$user instanceof User) {
            return new JsonResponse(['error' => true, 'error_message' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Check if the user has already completed this Defi
        if ($user->getDefisValid()->contains($defi)) {
            return new JsonResponse(['error' => true, 'error_message' => 'Defi is already done'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Verify the provided key
        if ($defi->getKey() === $key) {
            // Increment the user's score
            $user->setScoreTotal($user->getScoreTotal() + $defi->getScore());

            // Add the Defi to the user's collection
            $user->addDefiValid($defi);

            // Persist changes to the database
            $entityManager->flush();

            // Serialize and return success response
            return new JsonResponse(['error' => false, 'error_message' => '', 'data' => ["message" => "ok"]], JsonResponse::HTTP_OK);
        }
        return new JsonResponse(['error' => true, 'error_message' => 'Incorrect key'], JsonResponse::HTTP_UNAUTHORIZED);
    }

    #[Route('/get_left_menu_categories', name: 'get_left_menu_categories', methods: ['GET'])]
    public function getLeftMenuCategories(Request $request): JsonResponse
    {
        try {
            // Menu categories for the left sidebard
            $categories = [
                ["title" => "Tout les défis",           "img" => "bi bi-pencil-square",         "url" => "/all"],
                ["title" => "Collaboration",            "img" => "bi bi-cpu",                   "url" => "/collaboration"],
                ["title" => "Rétroingénierie",          "img" => "bi bi-arrow-repeat",          "url" => "/retroingenierie"],
                ["title" => "Base de donnée",           "img" => "bi bi-code-slash",            "url" => "/base_de_donnee"],
                ["title" => "Algorithmie",              "img" => "bi bi-bar-chart",             "url" => "/algorithmie"],
                ["title" => "Web",                      "img" => "bi bi-diagram-3",             "url" => "/web"],
                ["title" => "Stégonographie",           "img" => "bi bi-calculator",            "url" => "/stegonographie"],

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
    public function getSingleDefi(int $id): JsonResponse
    {
        $defi = $this->defiRepository->find($id);

        if (!$defi) {
            return new JsonResponse(['error' => true, 'error_message' => 'Defi not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = $this->serializer->serialize($defi, 'json', ['groups' => ['defi-read']]);
        return new JsonResponse(['error' => false, 'error_message' => '', 'data' => json_decode($data)], JsonResponse::HTTP_OK);
    }
}
