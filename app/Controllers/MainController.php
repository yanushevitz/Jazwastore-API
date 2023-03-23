<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Auth0\SDK\Auth0;
use Auth0\SDK\Configuration\SdkConfiguration;

class MainController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $response = $response->withHeader("Content-Type", "application/json");

        return $response;
    }

}