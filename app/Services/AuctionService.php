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
use TypeError;

class AuctionService
{

    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly Serializer $serializer,
        private readonly FileService $fileService
    ) {
    }

    public function fetchIndexAuctionsJson()
    {
        $auctions = $this->entityManager->getRepository(Auction::class)->findAll();
        $auctions = $this->serializer->normalize(["auctions" => $auctions]);
        $auctions = $auctions["auctions"];
        foreach ($auctions as &$auction) {
            $user = $this->entityManager->find(User::class, $auction['user']);
            $auction['user'] = $this->serializer->normalize($user);
        }

        $auctions = $this->serializer->encode($auctions, "json");
        return $auctions;
    }
    public function fetchAuctionJson(mixed $id)
    {
        if (!is_numeric($id)) {
            return $this->serializer->serialize(["code" => 1, "message" => "Auction number invalid"], 'json');
        }
        $auction = $this->entityManager->getRepository(Auction::class)->findOneBy(['id' => $id]);
        $auction = $this->serializer->normalize(["auction" => $auction]);
        $auction = $auction["auction"];

        $user = $this->entityManager->find(User::class, $auction['user']);
        $auction['user'] = $this->serializer->normalize($user);

        $auction = $this->serializer->encode($auction, "json");
        return $auction;
    }
    public function createAuction(array &$auctionData = null, UploadedFile &$file = null)
    {
        if ($auctionData === null || $file === null) {
            return $this->serializer->serialize("no data was provided", "json");
        }

        try {
            $filename = $this->fileService->moveFile($file);
        } catch (Throwable $e) {
            throw new Exception();
        }
        $auction = new Auction();
        if (
            array_key_exists("amount", $auctionData) &&
            array_key_exists("class", $auctionData) &&
            array_key_exists("description", $auctionData) &&
            array_key_exists("id", $auctionData) &&
            array_key_exists("status", $auctionData) &&
            array_key_exists("subject", $auctionData) &&
            array_key_exists("user", $auctionData) &&
            array_key_exists("date", $auctionData) &&
            array_key_exists("title", $auctionData)
        ) {
            try {
                $auction->setAmount($auctionData['amount']);
                $auction->setClass($auctionData['class']);
                $auction->setDate(new \DateTime($auctionData['date']));
                $auction->setDescription($auctionData['description']);
                $auction->setId((int) $auctionData['id']);
                $auction->setImg('/uploaded/' . $filename);
                $auction->setStatus($auctionData['status']);
                $auction->setSubject($auctionData['subject']);
                $auction->setTitle($auctionData['title']);
                $auction->setUser($this->entityManager->find(User::class, $auctionData['user']));
            } catch (Throwable $error) {
                return $this->serializer->serialize(["code" => $error->getCode(), "message" => "Unable to create auction", $error->getMessage()], 'json');
            }

            try {
                $this->entityManager->persist($auction);
                $this->entityManager->flush();
            } catch (Throwable $error) {
                return $this->serializer->serialize(["code" => $error->getCode(), "message" => "Unable to persist auction in database (check ID)", $error->getMessage()], "json");
            }
            return $this->serializer->serialize(["code" => 0, "message" => "auction was created", "auction-id" => $auction->getId()], "json");
        } else {
            return $this->serializer->serialize(["code" => 1, "message" => "Some auction fields are empty"], 'json');
        }
    }
    public function updateAuction(array &$auctionData = null, UploadedFile &$file = null)
    {
        if (!$auctionData['id']) {
            return $this->serializer->serialize(["code" => 1, "message" => "No auction provided"], 'json');
        }

        $auction = $this->entityManager->getRepository(Auction::class)->findOneBy(["id" => $auctionData['id']]);

        $user = $auction->getUser();
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $user]);
        $session = $user->getSession();
        if ($session !== $auctionData['hash']) {
            return $this->serializer->serialize(["code" => 1, "message" => "Session hash doesn't equal owner hash (potential data leak or developer retardness)"], 'json');
        }

        if ($file) {
            try {
                $filename = $this->fileService->moveFile($file);
                $auction->setImg('/uploaded/' . $filename);
            } catch (Throwable $e) {
                return $this->serializer->serialize(["code" => 1, "message" => "Unable to store photo (wtf)"], 'json');
            }
        }

        if (array_key_exists("amount", $auctionData)) {
            if ((null !== $auctionData['amount'])) {

                $auction->setAmount($auctionData['amount']);
            }
        }
        if (array_key_exists("class", $auctionData)) {
            if ((null !== $auctionData['class'])) {

                $auction->setClass($auctionData['class']);
            }
        }
        if (array_key_exists("description", $auctionData)) {
            if ((null !== $auctionData['description'])) {

                $auction->setDescription($auctionData['description']);
            }
        }
        if (array_key_exists("status", $auctionData)) {
            if ((null !== $auctionData['status'])) {

                $auction->setStatus($auctionData['status']);
            }
        }
        if (array_key_exists("subject", $auctionData)) {
            if ((null !== $auctionData['subject'])) {

                $auction->setSubject($auctionData['subject']);
            }
        }
        if (array_key_exists("title", $auctionData)) {
            if ((null !== $auctionData['title'])) {

                $auction->setTitle($auctionData['title']);
            }
        }

        try {
            $this->entityManager->persist($auction);
            $this->entityManager->flush();
        } catch (Throwable $error) {
            return $this->serializer->serialize(["code" => $error->getCode(), "message" => "Unable to persist auction in database", $error->getMessage()], "json");
        }
        return $this->serializer->serialize(["code" => 0, "message" => "auction was created", "auction-id" => $auction->getId()], "json");
    }

    public function deleteAuction(mixed $data)
    {

        if (!isset($data['auction-id']) || !isset($data['hash']) || !isset($data['user-id'])) {
            return $this->serializer->serialize(["code" => 1, "message" => "no auction-id, user-id or hash was provided"], "json");
        }


        $auction_id = (int) $data['auction-id'];
        $user_id = (int) $data['user-id'];
        $hash =  $data['hash'];

        // var_dump($auction_id);
        // var_dump($user_id);
        // var_dump($hash);
        // die();

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $user_id]);


        if ($user == null) {
            return $this->serializer->serialize(["code" => 1, "message" => "There is no user with provided ID"], 'json');
        }

        if ($user->getSession() != $hash) {
            return $this->serializer->serialize(["code" => 1, "message" => "Session hash doesn't equal owner hash (potential data leak or developer retardness)"], 'json');
        }


        $auction = $this->entityManager->getRepository(Auction::class)->findOneBy(['id' => $auction_id]);

        if ($auction == null) {
            return $this->serializer->serialize(["code" => 1, "message" => "There is no auction with provided ID"], 'json');
        }
        try {
            $this->entityManager->remove($auction);
            $this->entityManager->flush();
        } catch (Throwable $e) {
            return $this->serializer->serialize(["code" => 1, "message" => 'Unable to remove auction: "' . $e->getMessage() . '"',], "json");
        }
        return $this->serializer->serialize(["code" => 0, "message" => "Auction was removed"], "json");
    }

    public function getFromId(mixed $data)
    {
        $data = $this->serializer->decode($data, "json");
        if (!isset($data["user-id"])) {
            return $this->serializer->serialize(["code" => 1, "message" => "no user-id was provided"], "json");
        }
        $id = $data['user-id'];
        return $this->serializer->serialize($this->entityManager->getRepository(Auction::class)->findBy(['user' => $id]), "json");
    }
    public function validateAuction()
    {
    }
}
