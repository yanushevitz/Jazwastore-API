<?php

namespace App\Controllers;
use PDO;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
class MainController{

    public function index(RequestInterface $request, ResponseInterface $response){
        $response = $response->withHeader("Content-Type", "application/json");
        // $this->pdo->
        // phpinfo();
        return $response;
    }
   
}