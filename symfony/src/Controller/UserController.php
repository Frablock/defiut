<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Doctrine\ORM\EntityManagerInterface;

use DateTime;

use App\Repository\UserRepository;
use App\Entity\User;
use Exception;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;

final class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    /**
     * Token Generator Endpoint
     */
    #[Route('/api/login', name: 'login', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Token generator endpoint'
    )]
    public function login(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        // Extract credentials from request
        $data = json_decode($request->getContent(), true);

        $usermail = $data['usermail'] ?? null;
        $password = $data['password'] ?? null;

        if (!$usermail || !$password) {
            return new JsonResponse(['error' => true, 'error_message' => 'Missing username or password'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $user = $this->userRepository->findOneByMail($usermail);

            if (!$user instanceof User) {
                throw new AuthenticationException('Invalid1 credentials');
            }

            if (!password_verify($password, $user->getMotDePasse())) {
                throw new AuthenticationException('Invalid2 credentials');
            }

            // Generate token
            $token = md5($usermail) . "." . bin2hex(openssl_random_pseudo_bytes(80)); //$this->jwtManager->create($user);

            // adding the token to the db
            $user->setToken($token);
            $date = new DateTime();
            $date->modify('+15 days');
            $user->setTokenExpirationDate($date);

            $entityManager->flush();

            return new JsonResponse(['error' => false, 'error_message' => '', 'data' => ['token' => $token, 'expirationDate' => $date->format('Y-m-d')]], JsonResponse::HTTP_OK);
        } catch (AuthenticationException $e) {
            return new JsonResponse(['error' => true, 'error_message' => 'Invalid credentials' . $e->getMessage()], JsonResponse::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Password change endpoint
     */
    #[Route('/api/change_password', name: 'change_password', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'change password endpoint'
    )]
    public function changePassword(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        // Extract credentials from request
        $data = json_decode($request->getContent(), true);

        $usermail = $data['usermail'] ?? null;
        $password = $data['password'] ?? null;
        $new_password = $data['new_password'] ?? null;

        // Extract token from Authorization header
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return new JsonResponse(['error' => true, 'error_message' => 'Missing token'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!$usermail || !$password || !$new_password) {
            return new JsonResponse(['error' => true, 'error_message' => "Missing usermail or password"], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $user = $this->userRepository->findOneByToken($token);

            if (!$user instanceof User) {
                throw new AuthenticationException('Invalid credentials');
            }

            if (!password_verify($password, $user->getMotDePasse())) {
                throw new AuthenticationException('Invalid credentials');
            }

            // Changing the password
            $user->setMotDePasse(password_hash($new_password, PASSWORD_ARGON2ID));
            $entityManager->flush();

            return new JsonResponse(['error' => false, 'data' => ['changed_password' => 'ok'], 'error_message' => ""], JsonResponse::HTTP_OK);
        } catch (AuthenticationException $e) {
            return new JsonResponse(['error' => true, 'error_message' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Disconnect Endpoint (Token Invalidation)
     */
    #[Route('/api/logout', name: 'logout', methods: ['POST'])]
    public function logout(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        // Extract token from Authorization header
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return new JsonResponse(['error' => true, 'error_message' => 'Missing token'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = $this->userRepository->findOneByToken($token);

        // Generate token
        $token = bin2hex(openssl_random_pseudo_bytes(100)); //$this->jwtManager->create($user);

        // adding the token to the db
        $user->setToken($token);
        $entityManager->flush();

        return new JsonResponse(['error' => false, 'error_message' => '', 'data' => ['message' => 'Logged out successfully']], JsonResponse::HTTP_OK);
    }

    /**
     * Testing enspoint for token
     */
    #[Route('/api/token_validity_test', name: 'token_validity_test', methods: ['POST'])]
    public function token_validity_test(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        try{
            // Extract token from Authorization header
            $token = $request->headers->get('Authorization');
            if (!$token) {
                return new JsonResponse(['error' => true, 'error_message' => 'Missing token'], JsonResponse::HTTP_UNAUTHORIZED);
            }

            $user = $this->userRepository->findOneByToken($token);

            if (!$user instanceof User) {
                throw new AuthenticationException('Invalid credentials');
            }
            return new JsonResponse(['error' => false, 'error_message' => '', 'data' => ['message' => $user]], JsonResponse::HTTP_OK);
        }catch(Exception $e){
            return new JsonResponse(['error' => true, 'error_message' => "Votre connexion à expiré"], JsonResponse::HTTP_OK);
        }
    }

    /**
     * Get All user info for a user
     */
    #[Route('/api/user_info', name: 'user_info', methods: ['POST'])]
    public function user_info(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        // Extract token from Authorization header
        
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return new JsonResponse(['error' => true, 'error_message' => 'Missing token'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = $this->userRepository->findOneByToken($token);

        if (!$user instanceof User) {
            throw new AuthenticationException('Invalid credentials');
        }
        return new JsonResponse(['error' => false, 'error_message' => '', 'data' => [
            'mail' => $user->getMail(), 
            'score_total' => $user->getScoreTotal(),
            'creation_date' => $user->getCreationDate(),
            'last_co' => $user->getLastCo(),
            'username' => $user->getUsername(),
            'defis_recents' => $user->getRecentDefis()

        ]], JsonResponse::HTTP_OK);
    }
}
