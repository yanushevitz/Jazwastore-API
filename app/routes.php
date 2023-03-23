<?php

declare(strict_types=1);
namespace App;

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use App\MainController;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

return function (App $app) {
    // $app->get('/', function(RequestInterface $request, ResponseInterface $response){echo "hi"; return $response;});

    // $app->group('/users', function (Group $group) {
    //     $group->get('', ListUsersAction::class);
    //     $group->get('/{id}', ViewUserAction::class);
    // });
};
