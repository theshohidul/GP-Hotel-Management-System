<?php

use App\Service\Logger\Log;
use App\Utility\Config;
use App\Utility\User\Device;
use DI\ContainerBuilder;
use Medoo\Medoo;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use App\Service\Auth\JwtAuthService;
use Slim\Factory\ServerRequestCreatorFactory;

return function (ContainerBuilder $containerBuilder) {

    $containerBuilder->addDefinitions([
        Config::class => function(ContainerInterface $c){
            return new Config(config_path());
        },
    ]);

    //Adding monolog
    $containerBuilder->addDefinitions([

        LoggerInterface::class => function (ContainerInterface $c) {
            $config = $c->get(Config::class);

            $loggerSettings = $config->get('app.logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

    ]);

    //Adding database into container
    $containerBuilder->addDefinitions([
        'database' => function (ContainerInterface $c) {
            $config = $c->get(Config::class);
            $defaultConnection = $config->get('database.default');
            $dbSettings = $config->get('database.connections.' . $defaultConnection);

            $medoo = new Medoo();

            $medoo->attach($c->get(\App\Repository\BaseRepository::class));

            $medoo->connect([
                'database_type' => $dbSettings['type'],
                'database_name' => $dbSettings['dbname'],
                'server' => $dbSettings['host'],
                'port' => $dbSettings['port'],
                'username' => $dbSettings['user'],
                'password' => $dbSettings['password'],
            ]);

            return $medoo;
        }
    ]);


    //Adding JWT Auth service
    $containerBuilder->addDefinitions([

        JwtAuthService::class => function (ContainerInterface $c) {
            $config = $c->get(Config::class);
            $settings = $config->get('auth.jwt');

            $issuer = (string)$settings['issuer'];
            $lifetime = (int)$settings['lifetime'];
            $privateKey = (string)$settings['private_key'];
            $publicKey = (string)$settings['public_key'];

            return new JwtAuthService($issuer, $lifetime, $privateKey, $publicKey);
        },

    ]);

    $containerBuilder->addDefinitions([
       Device::class => function(ContainerInterface $c){

           $serverRequestCreator = ServerRequestCreatorFactory::create();
           $request = $serverRequestCreator->createServerRequestFromGlobals();

           return new Device($request);
       },

    ]);

    $containerBuilder->addDefinitions([
       Log::class => function(ContainerInterface $c){

           $serverRequestCreator = ServerRequestCreatorFactory::create();
           $request = $serverRequestCreator->createServerRequestFromGlobals();
           $config = $c->get(Config::class);
           $device = $c->get(Device::class);

           return new Log($config, $request, $device);
       },

    ]);


};
