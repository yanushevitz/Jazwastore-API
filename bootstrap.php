<?php
use DI\Container;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';



$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$containerBuilder = new ContainerBuilder();

$containerBindings = require __DIR__.'/app/dependencies.php';
$containerBindings($containerBuilder);

$container = $containerBuilder->build();

// $container = require __DIR__.'/app/dependencies.php';
AppFactory::setContainer($container);
return AppFactory::create(null, $container);