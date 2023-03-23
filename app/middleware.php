<?php

declare(strict_types=1);
namespace App;

use Src\Application\Middleware\SessionMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(SessionMiddleware::class);
    $app->addErrorMiddleware(true, true, false);
    // return $app;
};
