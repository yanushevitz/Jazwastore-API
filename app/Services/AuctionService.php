<?php

namespace App\Services;

use Doctrine\ORM\EntityManager;
use App\Entity\Auction;

class AuctionService{
    
    public function __construct(
        private readonly EntityManager $entityManager
    ) {
    }

    public function fetchIndexAuctions(){
        // TODO limit number of given auctions via config
        $auctions = $this->entityManager->getRepository(Auction::class)->findAll();
        return $auctions;
    }
}