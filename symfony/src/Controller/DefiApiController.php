<?php
// src/Controller/DefiApiController.php
namespace App\Controller;

use App\Entity\Defi;
use App\Entity\User;
use App\Entity\DefiFichier;
use App\Repository\DefiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;



#[Route('/api/defis', name: 'api_defi_')]
class DefiApiController extends AbstractController
{
    public function __construct(
        private readonly DefiRepository $defiRepository,
        private readonly SerializerInterface $serializer
    ) {}


    #[Route('', name: 'list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $startTime = microtime(true);

        // Récupération du paramètre GET et validation
        $startId = max(1, (int)$request->query->get('start_id', 1));

        // Récupération des défis avec pagination par ID
        $defis = $this->defiRepository->findNextDefis($startId, 10);

        // Sérialisation avec contexte de groupe pour les relations
        $data = $this->serializer->serialize($defis, 'json', [
            'groups' => ['defi-read']
        ]);

        //
        $beforeRepo = microtime(true);
        $defis = $this->defiRepository->findNextDefis($startId, 10);
        $afterRepo = microtime(true);
        $beforeSerial = microtime(true);
        $data = $this->serializer->serialize($defis, 'json', ['groups' => ['defi-read']]);
        $endTime = microtime(true);

        // Log timings
        error_log("Total time: " . ($endTime - $startTime) . "s");
        error_log("Repository time: " . ($afterRepo - $beforeRepo) . "s");
        error_log("Serializer time: " . ($endTime - $beforeSerial) . "s");
        return new JsonResponse($data, json: true);
    }

    

    #[Route('/try_key', name: 'try_key', methods: ['POST'])]
    public function try_key(Request $request): JsonResponse
    {

        // Retrieve parameters from the request body (JSON or form data)
        $id = $request->request->get('id') ?? json_decode($request->getContent(), true)['id'] ?? null;
        $key = $request->request->get('key') ?? json_decode($request->getContent(), true)['key'] ?? null;

        // Retrieve the Defi by ID
        $defi = $this->defiRepository->find($id);
        if (!$defi) {
            return new JsonResponse(['error' => 'Defi not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Get the current user
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new JsonResponse(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Check if the user has already completed this Defi
        if ($user->getDefis()->contains($defi)) {
            return new JsonResponse(['error' => 'Defi is already done'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Verify the provided key
        if ($defi->getKey() === $key) {
            // Increment the user's score
            $user->setScoreTotal($user->getScoreTotal() + $defi->getScore());

            // Add the Defi to the user's collection
            $user->addDefi($defi);

            // Persist changes to the database
            //$this->entityManager->flush();

            // Serialize and return success response
            $data = $this->serializer->serialize($defi, 'json', ['groups' => ['defi-read']]);
            return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
        }

        // Delay response to prevent brute force attacks
        sleep(2);
        return new JsonResponse(['error' => 'Incorrect key'], JsonResponse::HTTP_UNAUTHORIZED);
    }

    #[Route('/get_left_menu_categories', name: 'get_left_menu_categories', methods: ['GET'])]
    public function getLeftMenuCategories(Request $request): JsonResponse
    {
        $categories = array(
            ["title"=>"Nos défis",      "img" => "liens de l'image", "url" => "/defis"], 
            ["title"=>"Alorithmique",   "img" => "liens de l'image", "url" => "/alorithmique"],
            ["title"=>"Reverse",        "img" => "liens de l'image", "url" => "/reverse"],
            ["title"=>"Web",            "img" => "liens de l'image", "url" => "/web"],
            ["title"=>"Reverse2",        "img" => "liens de l'image", "url" => "/reverse2"],
        ); // Asset de test
        return new JsonResponse(['categories' => $categories], JsonResponse::HTTP_OK);
    }

    //Il faut placer cette fonction a la toute fin de cette classe, sinon les requêtes vont croire que les routes appelées sont des ID et vont venir ici
    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $defi = $this->defiRepository->find($id);

        if (!$defi) {
            return new JsonResponse(['error' => 'Defi not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = $this->serializer->serialize($defi, 'json', ['groups' => ['defi-read']]);
        return new JsonResponse($data, json: true);
    }
}
