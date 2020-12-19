<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;


return function (RouteCollectorProxy $app) {
    $app->get('/test', [\App\Controller\V1\User\UserCreateController::class, 'showUser']);
    $app->get('/abc', [\App\Controller\V1\User\UserListController::class, 'abc'])
        ->add(\App\Middleware\Utility\LocaleMiddleware::class);
    $app->get('/usageConsumption/{msisdn}', [\App\Controller\V1\User\UsageConsumptionController::class,'getUsageConsumption']);


    // Create a new customer
    $app->post('/customers', [\App\Controller\V1\Customer\CustomerController::class, 'signUp']);
    //get all customer
    $app->get('/customers', [\App\Controller\V1\Customer\CustomerController::class, 'getAllCustomer']);
    //get all customer
    $app->get('/customers/{id}', [\App\Controller\V1\Customer\CustomerController::class, 'getAllCustomer']);



    // Create a new room
    $app->post('/rooms', [\App\Controller\V1\Room\RoomController::class, 'createNew']);
    // Get All room
    $app->get('/rooms', [\App\Controller\V1\Room\RoomController::class, 'getAll']);
    // Get Single room
    $app->get('/rooms/{id}', [\App\Controller\V1\Room\RoomController::class, 'getSingle']);


    // Book a room
    $app->post('/rooms/{roomNumber}/book', [\App\Controller\V1\Room\RoomController::class, 'bookRoom']);


    //Make payment for a booking
    $app->post('/payment/{bookingId}', [\App\Controller\V1\Payment\PaymentController::class, 'makePayment']);

};
