<?php

declare(strict_types=1);
namespace App;

// use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use Src\Application\Settings\Settings;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Auth0\SDK\Auth0;
// use Src\Application\Settings\Settings;
use Src\Application\Settings\SettingsInterface;

return function (ContainerBuilder $containerBuilder) {

    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        Connection::class => function(ContainerInterface $c){
            return DriverManager::getConnection([
                'dbname' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASS'],
                'host' => $_ENV['DB_HOST'],
                'driver' => $_ENV['DB_DRIVER']
            ]);
        },
        EntityManager::class => function(ContainerInterface $c){
            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: array(__DIR__."/Entity"),
                isDevMode: true,
            );
            return new EntityManager($c->get(Connection::class), $config);
        },
        Serializer::class => function(ContainerInterface $c){
            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            return new Serializer($normalizers, $encoders);
        },
        SettingsInterface::class => function(){
            $settings = require "settings.php";
            return $settings($containerBuilder);
        },
        Auth0::class => function(ContainerInterface $c){
            return new Auth0([
                'domain' => $_ENV['AUTH0_DOMAIN'],
                'clientId' => $_ENV['AUTH0_CLIENT_ID'],
                'clientSecret' => $_ENV['AUTH0_CLIENT_SECRET'],
                'cookieSecret' => $_ENV['AUTH0_COOKIE_SECRET']
            ]);
        }
        
    ]);
};
