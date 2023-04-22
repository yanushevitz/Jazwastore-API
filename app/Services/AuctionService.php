<?php

namespace App\Services;

use App\Services\FileService;
use Doctrine\ORM\EntityManager;
use App\Entity\Auction;
use App\Entity\User;
use Exception;
use Slim\Psr7\UploadedFile;
use Symfony\Component\Serializer\Serializer;
use Throwable;

class AuctionService{
    
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly Serializer $serializer,
        private readonly FileService $fileService
    ) {
    }

    public function fetchIndexAuctionsJson(){
        $auctions = $this->entityManager->getRepository(Auction::class)->findAll();
        $auctions = $this->serializer->normalize(["auctions" => $auctions]);
        $auctions = $auctions["auctions"];
        foreach($auctions as &$auction){
            $user = $this->entityManager->find(User::class, $auction['user']);
            $auction['user'] = $this->serializer->normalize($user);
        }     
        
        $auctions = $this->serializer->encode($auctions, "json");
        return $auctions;
    }
    public function createAuction(array $auctionData, UploadedFile $file){


        try{
            $filename = $this->fileService->moveFile($file);
            
        } catch(Throwable $e){
            throw new Exception();
        }
        $auction = new Auction();
        $auction->setAmount($auctionData['amount']);
        $auction->setClass($auctionData['class']);
        $auction->setDate(new \DateTime($auctionData['date']));
        $auction->setDescription($auctionData['description']);
        $auction->setId($auctionData['id']);
        $auction->setImg('/uploaded/'.$filename);
        $auction->setStatus($auctionData['status']);
        $auction->setSubject($auctionData['subject']);
        $auction->setTitle($auctionData['title']);
        $auction->setUser($this->entityManager->find(User::class, $auctionData['user']));

        $auction = $this->serializer->serialize($auction, 'json');
        return $auction;
    }
}