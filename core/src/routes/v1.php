<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;


return function (RouteCollectorProxy $app) {
    $app->get('/test', [\App\Controller\V1\User\UserCreateController::class, 'showUser']);
    $app->get('/abc', [\App\Controller\V1\User\UserListController::class, 'abc'])
        ->add(\App\Middleware\Utility\LocaleMiddleware::class);
    $app->get('/usageConsumption/{msisdn}', [\App\Controller\V1\User\UsageConsumptionController::class,'getUsageConsumption']);
};
