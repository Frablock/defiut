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
    #[OA\Post(
        path: '/api/login',
        tags: ['Authentication'],
        summary: 'Authenticate user and get token',
        description: 'Returns a token for authenticated users',
        operationId: 'login',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'User credentials',
            content: new OA\JsonContent(
                required: ['usermail', 'password'],
                properties: [
                    new OA\Property(property: 'usermail', type: 'string', format: 'email', example: 'user@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123')
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful authentication',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'token', type: 'string', example: 'abc123.def456...'),
                    new OA\Property(property: 'expirationDate', type: 'string', format: 'date', example: '2023-12-31')
                ], type: 'object')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Missing username or password',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Missing username or password')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Invalid credentials',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Invalid credentials')
            ],
            type: 'object'
        )
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
    #[OA\Post(
        path: '/api/change_password',
        tags: ['Authentication'],
        summary: 'Change user password',
        description: 'Allows authenticated users to change their password',
        operationId: 'changePassword',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Password change request',
            content: new OA\JsonContent(
                required: ['usermail', 'password', 'new_password'],
                properties: [
                    new OA\Property(property: 'usermail', type: 'string', format: 'email', example: 'user@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'oldpassword123'),
                    new OA\Property(property: 'new_password', type: 'string', format: 'password', example: 'newpassword123')
                ]
            )
        )
    )]
    #[OA\Parameter(
        name: 'Authorization',
        in: 'header',
        required: true,
        description: 'Bearer token for authentication',
        schema: new OA\Schema(type: 'string', example: 'Bearer abc123.def456...')
    )]
    #[OA\Response(
        response: 200,
        description: 'Password successfully changed',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'changed_password', type: 'string', example: 'ok')
                ], type: 'object')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Missing token or required fields',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Missing token or required fields')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Invalid credentials',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Invalid credentials')
            ],
            type: 'object'
        )
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
     * Email change endpoint
     */
    #[Route('/api/change_email', name: 'change_email', methods: ['POST'])]
    #[OA\Post(
        path: '/api/change_email',
        tags: ['Authentication'],
        summary: 'Change user email',
        description: 'Allows authenticated users to change their email address. Requires current password for verification.',
        operationId: 'changeEmail',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Email change request',
            content: new OA\JsonContent(
                required: ['usermail', 'password', 'new_email'],
                properties: [
                    new OA\Property(property: 'usermail', type: 'string', format: 'email', example: 'user@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'currentpassword123'),
                    new OA\Property(property: 'new_email', type: 'string', format: 'email', example: 'new.email@example.com')
                ]
            )
        )
    )]
    #[OA\Parameter(
        name: 'Authorization',
        in: 'header',
        required: true,
        description: 'Bearer token for authentication',
        schema: new OA\Schema(type: 'string', example: 'Bearer abc123.def456...')
    )]
    #[OA\Response(
        response: 200,
        description: 'Email successfully changed',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'changed_email', type: 'string', example: 'ok')
                ], type: 'object')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Missing token or required fields, or invalid email format',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Missing token or required fields')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Invalid credentials',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Invalid credentials')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 409,
        description: 'Email already in use',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Email already in use')
            ],
            type: 'object'
        )
    )]
    public function changeEmail(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        // Extract credentials from request
        $data = json_decode($request->getContent(), true);

        $usermail = $data['usermail'] ?? null;
        $password = $data['password'] ?? null;
        $new_email = $data['new_email'] ?? null;

        // Extract token from Authorization header
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return new JsonResponse(['error' => true, 'error_message' => 'Missing token'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!$usermail || !$password || !$new_email) {
            return new JsonResponse(['error' => true, 'error_message' => "Missing usermail, password or new_email"], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Validate email format
        if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['error' => true, 'error_message' => "Invalid email format"], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $user = $this->userRepository->findOneByToken($token);

            if (!$user instanceof User) {
                throw new AuthenticationException('Invalid credentials');
            }

            // Verify current password
            if (!password_verify($password, $user->getMotDePasse())) {
                throw new AuthenticationException('Invalid credentials');
            }

            // Check if new email is already in use by another user
            $existingUser = $this->userRepository->findOneBy(['mail' => $new_email]);
            if ($existingUser && $existingUser->getId() !== $user->getId()) {
                return new JsonResponse(['error' => true, 'error_message' => 'Email already in use'], JsonResponse::HTTP_CONFLICT);
            }

            // Update email
            $user->setMail($new_email);
            $entityManager->flush();

            return new JsonResponse(['error' => false, 'data' => ['changed_email' => 'ok'], 'error_message' => ""], JsonResponse::HTTP_OK);
        } catch (AuthenticationException $e) {
            return new JsonResponse(['error' => true, 'error_message' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Username change endpoint
     */
    #[Route('/api/change_username', name: 'change_username', methods: ['POST'])]
    #[OA\Post(
        path: '/api/change_username',
        tags: ['Authentication'],
        summary: 'Change user username',
        description: 'Allows authenticated users to change their username.',
        operationId: 'changeUsername',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Username change request',
            content: new OA\JsonContent(
                required: ['new_username'],
                properties: [
                    new OA\Property(property: 'new_username', type: 'string', example: 'New Username')
                ]
            )
        )
    )]
    #[OA\Parameter(
        name: 'Authorization',
        in: 'header',
        required: true,
        description: 'Bearer token for authentication',
        schema: new OA\Schema(type: 'string', example: 'Bearer abc123.def456...')
    )]
    #[OA\Response(
        response: 200,
        description: 'Username successfully changed',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'changed_username', type: 'string', example: 'ok')
                ], type: 'object')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Missing token or required fields, or invalid username format',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Missing token or required fields')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Invalid credentials',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Invalid credentials')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 409,
        description: 'Username already in use',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Username already in use')
            ],
            type: 'object'
        )
    )]
    public function changeUsername(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        // Extract credentials from request
        $data = json_decode($request->getContent(), true);

        $username = $data['new_username'] ?? null;

        // Extract token from Authorization header
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return new JsonResponse(['error' => true, 'error_message' => 'Missing token'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!$username) {
            return new JsonResponse(['error' => true, 'error_message' => "Missing username"], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $user = $this->userRepository->findOneByToken($token);

            if (!$user instanceof User) {
                throw new AuthenticationException('Invalid credentials');
            }

            // Check if new email is already in use by another user
            $existingUser = $this->userRepository->findOneBy(['username' => $username]);
            if ($existingUser && $existingUser->getId() !== $user->getId()) {
                return new JsonResponse(['error' => true, 'error_message' => 'Username already in use'], JsonResponse::HTTP_CONFLICT);
            }

            // Update email
            $user->setUsername($username);
            $entityManager->flush();

            return new JsonResponse(['error' => false, 'data' => ['changed_username' => 'ok'], 'error_message' => ""], JsonResponse::HTTP_OK);
        } catch (AuthenticationException $e) {
            return new JsonResponse(['error' => true, 'error_message' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Disconnect Endpoint (Token Invalidation)
     */
    #[Route('/api/logout', name: 'logout', methods: ['POST'])]
    #[OA\Post(
        path: '/api/logout',
        tags: ['Authentication'],
        summary: 'Logout and invalidate current token',
        description: 'Invalidates the current authentication token, logging out the user',
        operationId: 'logout',
    )]
    #[OA\Parameter(
        name: 'Authorization',
        in: 'header',
        required: true,
        description: 'Bearer token for authentication',
        schema: new OA\Schema(type: 'string', example: 'Bearer abc123.def456...')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successfully logged out',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'message', type: 'string', example: 'Logged out successfully')
                ], type: 'object')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Missing token',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', example: 'Missing token')
            ],
            type: 'object'
        )
    )]
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
    #[OA\Post(
        path: '/api/token_validity_test',
        tags: ['Authentication'],
        summary: 'Test token validity',
        description: 'Checks if the provided authentication token is valid and returns user information if valid',
        operationId: 'tokenValidityTest',
    )]
    #[OA\Parameter(
        name: 'Authorization',
        in: 'header',
        required: true,
        description: 'Bearer token for authentication',
        schema: new OA\Schema(type: 'string', example: 'Bearer abc123.def456...')
    )]
    #[OA\Response(
        response: 200,
        description: 'Token validation result',
        content: new OA\JsonContent(
            oneOf: [
                new OA\Schema(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', enum: [false], description: 'False indicates success'),
                        new OA\Property(property: 'error_message', type: 'string', enum: [""], description: 'Empty string on success'),
                        new OA\Property(property: 'data', properties: [
                            new OA\Property(property: 'message', description: 'User object', type: 'object')
                        ], type: 'object')
                    ],
                    type: 'object',
                    description: 'Successful validation response'
                ),
                new OA\Schema(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', enum: [true], description: 'True indicates error'),
                        new OA\Property(property: 'error_message', type: 'string', description: 'Error message', example: 'Votre connexion à expiré')
                    ],
                    type: 'object',
                    description: 'Failed validation response'
                )
            ]
        )
    )]
    public function token_validity_test(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        try {
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
        } catch (Exception $e) {
            return new JsonResponse(['error' => true, 'error_message' => "Votre connexion à expiré"], JsonResponse::HTTP_OK);
        }
    }

    /**
     * Check if user has editor role
     */
    #[Route('/api/is_editor', name: 'is_editor', methods: ['POST'])]
    #[OA\Post(
        path: '/api/is_editor',
        tags: ['Authentication'],
        summary: 'Check if user has editor role',
        description: 'Checks if the authenticated user has editor role',
        operationId: 'isEditor'
    )]
    #[OA\Parameter(
        name: 'Authorization',
        in: 'header',
        required: true,
        description: 'Bearer token for authentication',
        schema: new OA\Schema(type: 'string', example: 'Bearer abc123.def456...')
    )]
    #[OA\Response(
        response: 200,
        description: 'Role check result',
        content: new OA\JsonContent(
            oneOf: [
                new OA\Schema(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', enum: [false], description: 'False indicates success'),
                        new OA\Property(property: 'error_message', type: 'string', enum: [""], description: 'Empty string on success'),
                        new OA\Property(property: 'data', properties: [
                            new OA\Property(property: 'message', description: 'Success message', type: 'string', example: 'User has editor role')
                        ], type: 'object')
                    ],
                    type: 'object',
                    description: 'Successful response'
                ),
                new OA\Schema(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', enum: [true], description: 'True indicates error'),
                        new OA\Property(property: 'error_message', type: 'string', description: 'Error message', example: 'Missing permission')
                    ],
                    type: 'object',
                    description: 'Failed response'
                )
            ]
        )
    )]
    public function is_editor(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        try {
            // Extract token from Authorization header
            $token = $request->headers->get('Authorization');
            if (!$token) {
                return new JsonResponse(['error' => true, 'error_message' => 'Missing token'], JsonResponse::HTTP_UNAUTHORIZED);
            }

            $user = $this->userRepository->findOneByToken($token);

            if (!$user instanceof User) {
                throw new AuthenticationException('Invalid credentials');
            }

            // Check if user has editor role
            if (!in_array("editor", $user->getRoles())) {
                return new JsonResponse(['error' => true, 'error_message' => 'Missing permission'], JsonResponse::HTTP_FORBIDDEN);
            }

            return new JsonResponse(['error' => false, 'error_message' => '', 'data' => ['message' => 'User has editor role']], JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            return new JsonResponse(['error' => true, 'error_message' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Get All user info for a user
     */
    #[Route('/api/user_info', name: 'user_info', methods: ['POST'])]
    #[OA\Post(
        path: '/api/user_info',
        tags: ['User'],
        summary: 'Get user information',
        description: 'Returns comprehensive information about the authenticated user',
        operationId: 'getUserInfo',
    )]
    #[OA\Parameter(
        name: 'Authorization',
        in: 'header',
        required: true,
        description: 'Bearer token for authentication',
        schema: new OA\Schema(type: 'string', example: 'Bearer abc123.def456...')
    )]
    #[OA\Response(
        response: 200,
        description: 'User information retrieved successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: false),
                new OA\Property(property: 'error_message', type: 'string', example: ''),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'mail', type: 'string', format: 'email', description: 'User email address', example: 'user@example.com'),
                    new OA\Property(property: 'score_total', type: 'integer', description: 'User total score', example: 1000),
                    new OA\Property(property: 'creation_date', type: 'string', format: 'date-time', description: 'User account creation date', example: '2023-01-01T12:00:00Z'),
                    new OA\Property(property: 'last_co', type: 'string', format: 'date-time', description: 'Last connection date', example: '2023-05-15T09:30:00Z'),
                    new OA\Property(property: 'username', type: 'string', description: 'Username', example: 'johndoe'),
                    new OA\Property(property: 'defis_recents', type: 'array', items: new OA\Items(type: 'object'), description: 'Recent challenges')
                ], type: 'object')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Authentication error (missing token or invalid credentials)',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'boolean', example: true),
                new OA\Property(property: 'error_message', type: 'string', description: 'Error message may be "Missing token" or "Invalid credentials"')
            ],
            type: 'object'
        )
    )]
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
