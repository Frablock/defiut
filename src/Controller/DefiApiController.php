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


#[Route('/api/defis', name: 'api_defi_')]
class DefiApiController extends AbstractController
{
    public function __construct(
        private readonly DefiRepository $defiRepository,
        private readonly SerializerInterface $serializer
    ) {
    }


    #[Route('', name: 'list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $startTime = microtime(true);
        // ... your existing code

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
