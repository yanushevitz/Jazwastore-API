<?php
use App\Controllers\MainController;
use Dotenv\Dotenv;
use DI\Container;
use Slim\Factory\AppFactory;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;

$app = require __DIR__.'/../bootstrap.php';
$middleware = require __DIR__."/../app/middleware.php";

$middleware($app);

$app->get('/' , [MainController::class, 'index']);

$container = $app->getContainer();

$em = $container->get(EntityManager::class);

$app->run();