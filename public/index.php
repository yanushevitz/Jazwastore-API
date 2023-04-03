<?php
use App\Controllers\MainController;

$app = require __DIR__.'/../bootstrap.php';
$middleware = require __DIR__."/../app/middleware.php";

$middleware($app);

$app->get('/' , [MainController::class, 'index']);
$app->post('/create' , [MainController::class, 'create']);

$app->run();