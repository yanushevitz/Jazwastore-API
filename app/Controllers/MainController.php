<?php

namespace App\Controllers;

use App\Entity\Auction;
use App\Entity\User;
use Auth0\SDK\Auth0;
use Doctrine\ORM\EntityManager;
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
        private readonly Serializer $serializer,
        private readonly Auth0 $auth0,
        private readonly EntityManager $em
    ) {
    }

    
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $response = $response->withHeader("Content-Type", "application/json");
        $indexAuctions = $this->auctionService->fetchIndexAuctionsJson();
        $response->getBody()->write($indexAuctions);
        return $response;
    }
    public function create(RequestInterface $request, ResponseInterface $response)
    {
        $payload = $request->getBody();
        var_dump($payload);
        // $response->getBody()->write(json_encode(['a' => 'b']));`
        return $response;
    }


    
}