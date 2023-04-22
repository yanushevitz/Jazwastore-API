<?php

$app = require __DIR__.'/../bootstrap.php';
$middleware = require __DIR__."/../app/middleware.php";
$routes = require __DIR__."/../app/routes.php";

$middleware($app);
$routes($app);

$app->run();
