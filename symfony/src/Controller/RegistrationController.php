<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;

use DateTime;

use App\Repository\UserRepository;
use App\Entity\User;

use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;

final class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'app_registration', methods: ['POST'])]
    #[OA\Post(
        path: '/api/register',
        tags: ['Authentication'],
        summary: 'Register a new user',
        description: 'Creates a new user account with the provided credentials',
        operationId: 'registerUser',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'User registration details',
            content: new OA\JsonContent(
                required: ['usermail', 'password', 'username'],
                properties: [
                    new OA\Property(property: 'usermail', type: 'string', format: 'email', description: 'User email address', example: 'user@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', description: 'User password', example: 'securePassword123'),
                    new OA\Property(property: 'username', type: 'string', description: 'User username', example: 'johndoe')
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'User registered successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'token', type: 'string', description: 'Authentication token', example: 'abc123.def456...'),
                    new OA\Property(property: 'expirationDate', type: 'string', format: 'date', description: 'Token expiration date', example: '2023-12-31')
                ], type: 'object')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request (validation errors)',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(
                    property: 'error_message',
                    type: 'string',
                    example: [
                        'Missing required fields.',
                        'Invalid email format.',
                        'Email already in use.',
                        'Username already in use.'
                    ],
                    description: 'Error message explaining the validation issue'
                )
            ],
            type: 'object'
        )
    )]
    public function register(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        // Get the data
        $data = json_decode($request->getContent(), true);

        $usermail = $data['usermail'] ?? null;
        $password = $data['password'] ?? null;
        $username = $data['username'] ?? null;

        // Validating inputs
        if (empty($usermail) || empty($password) || empty($username)) {
            return new JsonResponse(['error' => true, 'error_message' => 'Missing required fields.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Email validation
        if (!filter_var($usermail, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['error' => true, 'error_message' => 'Invalid email format.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Email unicity verification
        $userRepository = $entityManager->getRepository(User::class);
        $existingUser = $userRepository->findOneBy(['mail' => $usermail]);
        if ($existingUser) {
            return new JsonResponse(['error' => true, 'error_message' => 'Email already in use.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Username unicity verification
        $existingUsername = $userRepository->findOneBy(['username' => $username]);
        if ($existingUsername) {
            return new JsonResponse(['error' => true, 'error_message' => 'Username already in use.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = new User();

        $user->setMail($usermail);
        $user->setUsername($username);
        $user->setMotDePasse(password_hash($password, PASSWORD_ARGON2ID));
        $user->setCreationDate(new DateTime());
        $user->setLastCo(new DateTime());

        $token = md5($usermail) . "." . bin2hex(openssl_random_pseudo_bytes(80)); //$this->jwtManager->create($user);

        // adding the token to the db
        $user->setToken($token);
        $date = new DateTime();
        $date->modify('+15 days');
        $user->setTokenExpirationDate($date);

        // Add the user to the DB
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['error' => false, 'error_message' => '', 'data' => ['token' => $token, 'expirationDate' => $date->format('Y-m-d')]], JsonResponse::HTTP_OK);
    }
}
