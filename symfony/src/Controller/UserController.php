<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
//use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
//use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

use DateTime;

use App\Repository\UserRepository;
use App\Entity\User;

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
        $usermail = $request->request->get('usermail');
        $password = $request->request->get('password');

        if (!$usermail || !$password) {
            return new JsonResponse(['error' => 'Missing username or password'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $user = $this->userRepository->findOneByMail($usermail);

            if (!$user instanceof User) {
                throw new AuthenticationException('Invalid credentials');
            }

            if (!password_verify($password, $user->getMotDePasse())) {
                throw new AuthenticationException('Invalid credentials');
            }

            // Generate token
            $token = md5($usermail).".".bin2hex(openssl_random_pseudo_bytes(80));//$this->jwtManager->create($user);

            // adding the token to the db
            $user->setToken($token);
            $date = new DateTime();
            $date->modify('+15 days');
            $user->setTokenExpirationDate($date);

            $entityManager->flush();

            return new JsonResponse(['token' => $token, 'expirationDate' => $date->format('Y-m-d')], JsonResponse::HTTP_OK);
        } catch (AuthenticationException $e) {
            return new JsonResponse(['error' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
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
        $usermail = $request->request->get('usermail');
        $password = $request->request->get('password');

        // Extract token from Authorization header
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return new JsonResponse(['error' => 'Missing token'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        if (!$usermail || !$password) {
            return new JsonResponse(['error' => 'Missing username or password'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $user = $this->userRepository->findOneByMail($usermail);

            if (!$user instanceof User) {
                throw new AuthenticationException('Invalid credentials');
            }

            if (!password_verify($password, $user->getMotDePasse())) {
                throw new AuthenticationException('Invalid credentials');
            }

            // Changing the password
            $user->setMotDePasse(password_hash($password, PASSWORD_ARGON2ID));
            $entityManager->flush();

            return new JsonResponse(['changed_password' => 'ok'], JsonResponse::HTTP_OK);
        } catch (AuthenticationException $e) {
            return new JsonResponse(['error' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
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
            return new JsonResponse(['error' => 'Missing token'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = $this->userRepository->findOneByToken($token);

        // Generate token
        $token = bin2hex(openssl_random_pseudo_bytes(100));//$this->jwtManager->create($user);

        // adding the token to the db
        $user->setToken($token);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Logged out successfully'], JsonResponse::HTTP_OK);
    }

    /**
     * Testing enspoint for token
     */
    #[Route('/api/token_validity_test', name: 'token_validity_test', methods: ['POST'])]
    public function token_validity_test(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        // Extract token from Authorization header
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return new JsonResponse(['error' => 'Missing token'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = $this->userRepository->findOneByToken($token);
        return new JsonResponse(['message' => $user], JsonResponse::HTTP_OK);
    }
}
