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

        if (in_array("editor", $user->getRoles())) {
            return new JsonResponse(['error' => true, 'error_message' => 'Missing permission'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Extract credentials from request
        $data = json_decode($request->getContent(), true);

        $nom = $data['nom'] ?? null;
        $desc = $data['desc'] ?? null;
        $diff = $data['diff'] ?? null;
        $key = $data['key'] ?? null;
        $score = $data['score'] ?? null;

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
