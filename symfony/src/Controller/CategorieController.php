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

/**
 * Controller to serve the lobby categories endpoint
 *
 * Provides the top tags and the recent challenges of an authenticated user.
 */
class CategorieController extends AbstractController
{
    /**
     * GET /api/defis/get_lobby_categories
     *
     * Returns:
     * - tags_name: array of top 5 tag titles
     * - defis_recents: array of recent challenges for the user (title + id), empty if not authenticated
     *
     * @param EntityManagerInterface $em   Doctrine entity manager
     * @param Request                $request HTTP request to access headers
     * @return JsonResponse Structured JSON response with error flag and data
     */
    #[Route('/api/defis/get_lobby_categories', name: 'get_lobby_categories', methods: ['GET'])]
    public function getCategories(EntityManagerInterface $em, Request $request): JsonResponse
    {
        try{
            $tagRepository = $em->getRepository(Tag::class);

            $tags = $tagRepository->getTop5Tags();
            $tagsName = [];
            $recentDefisArray = [];
            // Build list of tag titles for the response
            foreach ($tags as $tag) {
                $tagsName[] = [
                    'title' => $tag->getNom(),
                ];
            }

            $token = $request->headers->get('Authorization');
            if ($token) {
                // Find the User by their stored token
                $user = $em->getRepository(User::class)->findOneByToken($token);
                if ($user instanceof User) {
                    // Get the collection of recent challenges for that user
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
                        'tags_name' => $tagsName, // Top 5 tag titles
                        'defis_recents' => $recentDefisArray // Recent challenges for the user (may be empty)
                    ],
                ]
            );
        }catch(Exception $e){

        }

    }
}