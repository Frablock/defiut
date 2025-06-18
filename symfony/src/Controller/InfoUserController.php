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


class InfoUserController extends AbstractController
{

    #[Route('/api/get_info_user', name: 'get_info_user', methods: ['GET'])]
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