<?php

define('BASE_PATH', dirname( __DIR__));
define('APP_START_TIME', microtime(true));

/**
 * Autoload dependencies
 */
require __DIR__ . '/../vendor/autoload.php';

/**
 * Boot up the application
 */
$app = require base_path('bootstrap/app.php');

/**
 * App is ready to take request
 */
$app->run();