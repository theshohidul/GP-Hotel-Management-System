<?php

namespace App\Middleware\Cache;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CacheMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param ServerRequest $request PSR-7 request
     * @param RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        if (!$response->hasHeader('Cache-Control'))
            $response = $response->withHeader('Cache-Control', 'max-age=2');
        return $response;
    }
}