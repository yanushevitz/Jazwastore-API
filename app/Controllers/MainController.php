<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Services\AuctionService;
use Symfony\Component\Serializer\Serializer;

class MainController
{
    /**
     */
    public function __construct(
        private readonly AuctionService $auctionService,
        private readonly Serializer $serializer
    ) {
    }

    
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $response = $response->withHeader("Content-Type", "application/json");
        $indexAuctions = $this->auctionService->fetchIndexAuctions();
        $serializedData = $this->serializer->serialize(["auctions" => $indexAuctions], 'json');
        $response->getBody()->write($serializedData);
        return $response;
    }


    
}