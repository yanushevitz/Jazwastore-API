<?php

namespace App\Controllers;

use App\Services\FileService;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Services\AuctionService;
use Symfony\Component\Serializer\Serializer;
use App\Services\UserService;

class MainController
{
    /**
     */
    public function __construct(
        private readonly AuctionService $auctionService,
        private readonly FileService $fileService,
        private readonly EntityManager $em,
        private readonly Serializer $serializer,
        private readonly UserService $userService
    ) {
    }


    public function index(RequestInterface $request, ResponseInterface $response)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        $response = $response->withHeader("Content-Type", "application/json");
        $indexAuctions = $this->auctionService->fetchIndexAuctionsJson();
        $response->getBody()->write($indexAuctions);
        return $response;
    }
    public function tymczas(RequestInterface $request, ResponseInterface $response)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        $response = $response->withHeader("Content-Type", "application/json");
        $userzy = $this->userService->fetchAll();
        $response->getBody()->write($userzy);
        return $response;
    }
    public function auctions(RequestInterface $request, ResponseInterface $response)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        $response = $response->withHeader("Content-Type", "application/json");
        $auctions = $this->auctionService->getFromId($request->getBody()->getContents());

        $response->getBody()->write($auctions);
        return $response;
    }

    /**
     * REQUEST:
     * 1. id
     * 2. title
     * 3. description
     * 4. amount
     * 5. status
     * 6. date
     * 7. class
     * 8. subject
     * 9. img (resource)
     * 10. user (id)
     * 
     */
    public function create(RequestInterface $request, ResponseInterface $response)
    {
        $response->withHeader("Content-Type", "application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");

        $auctionData = $request->getParsedBody();
        $file = $request->getUploadedFiles()['img'];
        $auction = $this->auctionService->createAuction($auctionData, $file);
        $response->getBody()->write($auction);
        return $response;
    }
    public function update(RequestInterface $request, ResponseInterface $response)
    {
        $response->withHeader("Content-Type", "application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");

        $auctionData = $request->getParsedBody();
        $file = $request->getUploadedFiles()['img'];
        $auction = $this->auctionService->updateAuction($auctionData, $file);
        $response->getBody()->write($auction);
        return $response;
    }
    public function delete(RequestInterface $request, ResponseInterface $response)
    {
        $response = $response->withHeader("Content-Type", "application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        $data = $request->getParsedBody();
        $result = $this->auctionService->deleteAuction($data);
        $response->getBody()->write($result);
        return $response;
    }
    public function view(RequestInterface $request, ResponseInterface $response)
    {
        $response = $response->withHeader("Content-Type", "application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        $data = $request->getParsedBody();
        $result = $this->auctionService->fetchAuctionJson($data["id"]);
        $response->getBody()->write($result);
        return $response;
    }

    public function login(RequestInterface $request, ResponseInterface $response)
    {
        $response->withHeader("Content-Type", "application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");

        $loginData = $request->getParsedBody();
        $login = $this->userService->login($loginData);
        $response->getBody()->write($login);

        return $response;
    }
    public function logout(RequestInterface $request, ResponseInterface $response)
    {
        $response->withHeader("Content-Type", "application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        $response->getBody()->write($this->userService->logout($request->getParsedBody()));
        return $response;
    }
    public function deleteUser(RequestInterface $request, ResponseInterface $response)
    {
        $response->withHeader("Content-Type", "application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        $response->getBody()->write($this->userService->deleteUser($request->getParsedBody()));
        return $response;
    }

    public function register(RequestInterface $request, ResponseInterface $response)
    {
        $response->withHeader("Content-Type", "application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        $response->getBody()->write($this->userService->register($request->getParsedBody()));
        return $response;
    }
    public function maf(RequestInterface $request, ResponseInterface $response)
    {
        $response->withHeader("Content-Type", "application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");

        $response = $response->withStatus(402);
        $response->getBody()->write("{'img' => 'https://i.ibb.co/PrRfNmz/dfbi34f-4bbadba9-26b2-4416-98a5-dcb2bf580669.jpg'}");
        return $response;
    }
}
