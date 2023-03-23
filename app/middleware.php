<?php

declare(strict_types=1);
namespace App;

use App\Application\Middleware\SessionMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(SessionMiddleware::class);
    // $app->addErrorMiddleware(true, true, true);
};
