<?php

namespace App\Services;

use Doctrine\ORM\EntityManager;
use App\Entity\Auction;
use App\Entity\User;
use Symfony\Component\Serializer\Serializer;

class AuctionService{
    
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly Serializer $serializer
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
}