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
use Exception;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class CategorieController extends AbstractController
{
    #[Route('/api/defis/get_lobby_categories', name: 'get_lobby_categories', methods: ['GET'])]
    public function getCategories(EntityManagerInterface $em, Request $request): JsonResponse
    {
        try{
            $tagRepository = $em->getRepository(Tag::class);

            $tags = $tagRepository->getTop5Tags();
            $tagsName = [];
            $recentDefisArray = [];

            foreach ($tags as $tag) {
                $tagsName[] = [
                    'title' => $tag->getNom(),
                ];
            }

            $token = $request->headers->get('Authorization');
            if ($token) {
                $user = $em->getRepository(User::class)->findOneByToken($token);
                if ($user instanceof User) {
                    $recentDefis = $user->getRecentDefis();
                    foreach ($recentDefis as $recentDefi) {
                        $recentDefisArray[] = [
                            'title' => $recentDefi->getNom(),
                            'id'=> $recentDefi->getId()
                        ];
                    }
                }
            }
            return new JsonResponse(
                [
                    'error'=> false,
                    'data'=> [
                        'tags_name' => $tagsName, 
                        'defis_recents' => $recentDefisArray
                    ],
                ]
            );
        }catch(Exception $e){

        }

    }
}