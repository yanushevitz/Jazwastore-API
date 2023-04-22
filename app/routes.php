<?php

declare(strict_types=1);
namespace App;

use Slim\App;
use App\Controllers\MainController;

return function (App $app) {
    $app->get('/' , [MainController::class, 'index']);
    $app->post('/create' , [MainController::class, 'create']);
};
