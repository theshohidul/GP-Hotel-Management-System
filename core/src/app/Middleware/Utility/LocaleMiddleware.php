<?php

namespace App\Middleware\Utility;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

/**
 * Locale middleware.
 */
final class LocaleMiddleware
{

    /**
     * @param Request $request
     * @param RequestHandler $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        if (!$request->hasHeader('Accept-Language')) {
            $request = $request->withHeader('Accept-Language', 'en');
        }

        return $handler->handle($request);

    }
}