<?php


use Slim\App;
use Slim\Factory\ServerRequestCreatorFactory;
use App\Handler\HttpErrorHandler;
use App\Handler\ShutdownHandler;

/**
 * set up all middleware for the application
 */

return function (App $app)
{
    $config = $app->getContainer()->get(\App\Utility\Config::class);
    $settings = $config->get('app');
    $log = $app->getContainer()->get(\App\Service\Logger\Log::class);
    $serverRequestCreator = ServerRequestCreatorFactory::create();
    $request = $serverRequestCreator->createServerRequestFromGlobals();

    // log request related data
    $log->set('request', [
        'start_at' => APP_START_TIME,
        'request_id' => REQUEST_UUID,
        'headers' => $request->getHeaders(),
        'query' => $request->getQueryParams()
    ], 'request');

    $log->set('request.payload', $request->getParsedBody(), 'request.accept_payload');

    //slim4 default handler for body parsing and routing
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();

    $app->add(new \App\Middleware\Cache\CacheMiddleware());

    $errorMiddleware = $app->addErrorMiddleware(
        $settings['displayErrorDetails'] ?? false,
        $settings['logErrorDetails'] ?? false,
        $settings['logErrors'] ?? false
    );

    //custom error handler
    $callableResolver = $app->getCallableResolver();
    $responseFactory = $app->getResponseFactory();
    $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

    //setting up custom shutdown handler
    $shutdownHandler = new ShutdownHandler($request, $errorHandler, $settings['displayErrorDetails'] ?? false);
    $log->set('request.headers', $request->getHeaders(), 'url');
    $log->set('request.url', $request->getUri()->getPath(), 'url');
    register_shutdown_function($shutdownHandler);

    //setting up custom error handler
    $errorMiddleware->setDefaultErrorHandler($errorHandler);
};
