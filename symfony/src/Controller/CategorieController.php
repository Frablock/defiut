<?php
namespace App\Controller;

use App\Entity\RecentDefi;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class CategorieController extends AbstractController
{
    #[Route('/api/defis/get_lobby_categories', name: 'get_lobby_categories', methods: ['GET'])]
    public function getCategorie(EntityManagerInterface $em, Request $request): JsonResponse
    {
        $tagRepository = $em->getRepository(Tag::class);

        $tags = $tagRepository->findAll();

        $tagsAvecStats = [];

        foreach ($tags as $tag) {
            $tagsAvecStats[] = [
                'nom' => $tag->getNom(),
                'nb_defis' => count($tag->getDefis()),
            ];
        }

        usort($tagsAvecStats, fn($a, $b) => $b['nb_defis'] <=> $a['nb_defis']);

        $tendance = array_column($tagsAvecStats, 'nom');

        // Extract token from Authorization header
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return new JsonResponse(['error' => true, 'error_message' => 'Missing token'], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $user = $em->getRepository(RecentDefi::class)->findOneByToken($token);

        if (!$user instanceof User) {
            throw new AuthenticationException('Invalid credentials');
        }

        $recents = [];

        if (!$user) {
            $recents[] = ['error' => 'Utilisateur non authentifié'];
        } else {
            /** @var RecentDefi[] $ents */
            $ents = $em->getRepository(RecentDefi::class)
                ->findBy(
                    ['user' => $user],
                    ['dateAcces' => 'DESC'],
                    50
                );

            $seen = [];
            foreach ($ents as $r) {
                $nom = $r->getDefi()->getNom();
                if (in_array($nom, $seen, true)) {
                    continue;
                }
                $seen[]    = $nom;
                $recents[] = ['nom' => $nom];

                if (count($recents) >= 10) {
                    break;
                }
            }

            // Si l'utilisateur est connecté mais n'a aucun accès enregistré
            if (empty($recents)) {
                $recents[] = ['message' => 'Aucun défi récent trouvé'];
            }
        }

        return new JsonResponse([
            'categories' => [
                'tendance' => $tendance,
                'recents'  => $recents,
            ],
        ]);
    }
}