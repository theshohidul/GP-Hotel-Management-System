<?php

namespace App\Middleware\Auth;

use App\Service\Auth\JwtAuthService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

/**
 * JWT Auth middleware.
 */
final class AuthMiddleware
{
    /**
     * @var JwtAuthService
     */
    private $jwtAuth;

    /**
     * AuthMiddleware constructor.
     * @param JwtAuthService $jwtAuth
     */
    public function __construct(JwtAuthService $jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
    }


    /**
     * @param Request $request
     * @param RequestHandler $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $token = explode(' ', (string)$request->getHeaderLine('Authorization'))[1] ?? '';

        $response = new Response();

        if (!$token || !$this->jwtAuth->validateToken($token)) {
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401, 'Unauthorized');
        }

        return $handler->handle($request);
    }
}