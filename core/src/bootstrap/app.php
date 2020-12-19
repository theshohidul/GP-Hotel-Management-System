<?php

use DI\ContainerBuilder;
use DI\Bridge\Slim\Bridge as SlimAppFactory;

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

//Loading .env file
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    Dotenv\Dotenv::createImmutable(dirname($envFile))->load();
}
// Resolve dependencies
$dependencies = require  base_path('/bootstrap/ioc.php');
$dependencies($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

$log = $container->get(\App\Utility\Logger\Log::class);
$log->set('app.start_at', APP_START_TIME);
// Instantiate the config
$app = SlimAppFactory::create($container);

// Set up middleware
$middleware = require base_path('/bootstrap/middleware.php');
$middleware($app);

// Set up routes
route_register($app);

return $app;