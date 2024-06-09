<?php

declare(strict_types=1);
namespace App;

use Slim\App;
use App\Controllers\MainController;

return function (App $app) {
    $app->get('/', [MainController::class, 'index']);
    $app->any('/user/index', [MainController::class, 'tymczas']);
    $app->post('/user/register', [MainController::class, 'register']);
    $app->post('/user/login', [MainController::class, 'login']);
    $app->post('/user/logout', [MainController::class, 'logout']);
    $app->post('/user/auctions', [MainController::class, 'auctions']);
    $app->post('/user/delete', [MainController::class, 'deleteUser']);
    $app->post('/auction', [MainController::class, 'view']);
    $app->post('/auction/create', [MainController::class, 'create']);
    $app->post('/auction/delete', [MainController::class, 'delete']);
    $app->post('/auction/update', [MainController::class, 'update']);

};