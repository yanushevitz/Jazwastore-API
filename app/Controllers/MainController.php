<?php

namespace App\Controllers;

use App\Services\FileService;
use Auth0\SDK\Auth0;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Services\AuctionService;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Serializer;
use Throwable;

class MainController
{
    /**
     */
    public function __construct(
        private readonly AuctionService $auctionService,
        private readonly FileService $fileService,
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
        $auctionData = $request->getParsedBody();
        $file = $request->getUploadedFiles()['img'];
        $auction = $this->auctionService->createAuction($auctionData, $file);
        $response->withHeader("Content-Type", "application/json");
        // $response->withHeader("Access-Control-Allow-Origin", "*");
        header("Access-Control-Allow-Origin: *");
        $response->getBody()->write($auction);
        return $response;
    }
}