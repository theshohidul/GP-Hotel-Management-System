<?php


namespace App\Controller\V1\Auth;


use App\Service\Auth\AuthService;
use App\Service\Auth\JwtAuthService;

/**
 * Class AuthController
 * @package Controller\Auth
 */
class AuthController
{

    /**
     * @var JwtAuthService
     */
    private $jwtAuth;

    /**
     * @var
     */
    private $authService;

    /**
     * AuthController constructor.
     * @param JwtAuthService $jwtAuth
     * @param AuthService $authService
     */
    public function __construct(JwtAuthService $jwtAuth, AuthService $authService)
    {
        $this->jwtAuth = $jwtAuth;
        $this->authService = $authService;
    }

    /**
     * @param $request
     * @param $response
     * @return mixed
     */
    public function signIn($request, $response)
    {
        $data = (array)$request->getParsedBody();

        $email = (string)($data['email'] ?? '');
        $password = (string)($data['password'] ?? '');


        $user = $this->authService->auth($email, $password);

        if (!$user) {
            // Invalid authentication credentials
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401, 'Unauthorized');
        }

        // Create a fresh token
        $token = $this->jwtAuth->createJwt([
            'uid' => $user['id'],
            'email' => $user['email'],
        ]);

        $lifetime = $this->jwtAuth->getLifetime();

        $result = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $lifetime,
        ];

        // Build the HTTP response
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write((string)json_encode($result));

        return $response->withStatus(200);
    }
}