<?php

namespace App\Services;

use App\Entity\Auction;
use DateTime;
use Doctrine\ORM\EntityManager;
use App\Entity\User;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Serializer;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserService
{
    public function __construct(
        private readonly Serializer $serializer,
        private readonly EntityManager $entityManager
    ) {
    }

    public function login(array $loginData)
    {

        if (array_key_exists("login", $loginData) && array_key_exists("password", $loginData)) {
            $login = $loginData['login'];
            $password = $loginData['password'];

            $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $login]);
            if (!$user || ($user->getPassword() !== $password)) {
                return $this->serializer->serialize(["code" => 1, "message" => "Wrong password or username"], "json");
            }
            if ($user->getSession() && (new DateTime)->diff($user->getLastInteraction())->i < 30) {
                return $this->serializer->serialize(["code" => 0, "message" => "User is already logged in and session is active!"], "json");
            }
            $key = "morenka";
            $hash = bin2hex(random_bytes(18));
            $user->setSession($hash);
            $user->setLastInteraction(new DateTime("now"));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->serializer->serialize(['code' => 0, "message" => "logged successfully", "hash" => $hash, "user" => JWT::encode($this->serializer->normalize($user), $key, 'HS256')], "json");
        } else {
            return $this->serializer->serialize(["code" => 2, "message" => "no login or password provided"], "json");
        }
    }
    public function logout(mixed $requestBody)
    {
        if (!is_array($requestBody)) {
            return $this->serializer->serialize(['code' => 1, "message" => "request is not in form-data format!"], 'json');
        }
        if (!array_key_exists('id', $requestBody) || !array_key_exists("hash", $requestBody)) {
            return $this->serializer->serialize(['code' => 1, "message" => "no hash or userId provided"], 'json');
        }
        $userId = $requestBody['id'];
        $hash = $requestBody['hash'];

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);
        if (!$user) {
            return $this->serializer->serialize(['code' => 1, "message" => "user does not exist lol"], 'json');
        }
        if (!$user->getSession()) {
            return $this->serializer->serialize(['code' => 1, "message" => "user was not logged in"], 'json');
        }

        if ($user->getSession() !== $hash) {
            return $this->serializer->serialize(['code' => 1, "message" => "hash does not match with user id (possible account leak)"], 'json');
        }

        $user->setLastInteraction(new DateTime("now"));
        $user->setSession(null);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->serializer->serialize(['code' => 0, "message" => "user logged out successfully"], 'json');
    }
    public function register(mixed $requestBody)
    {

        if (!is_array($requestBody)) {
            return $this->serializer->serialize(['code' => 1, "message" => "request is not in form-data format!"], 'json');
        }
        if (!array_key_exists("username", $requestBody) || !array_key_exists("password", $requestBody) || !array_key_exists("mail", $requestBody)) {
            return $this->serializer->serialize(['code' => 1, "message" => "no username, mail or password provided"], 'json');
        }
        $username = $requestBody['username'];
        $password = $requestBody['password'];
        $mail = $requestBody['mail'];

        if ($this->entityManager->getRepository(User::class)->findOneBy(["username" => $username])) {
            return $this->serializer->serialize(['code' => 1, "message" => "user with given username already exists"], 'json');
        }
        if ($this->entityManager->getRepository(User::class)->findOneBy(["email" => $mail])) {
            return $this->serializer->serialize(['code' => 1, "message" => "user with given email already exists"], 'json');
        }

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setEmail($mail);

        if (array_key_exists("facebook", $requestBody)) {
            $facebook = $requestBody['facebook'];
            $user->setFacebook($facebook);
        }

        if (array_key_exists("number", $requestBody)) {
            $number = $requestBody['number'];
            if ($this->entityManager->getRepository(User::class)->findOneBy(["number" => $number])) {
                return $this->serializer->serialize(['code' => 1, "message" => "user with given number already exists"], 'json');
            }

            $user->setNumber($number);
        }

        $id = random_int(0, 10000);
        while ($this->entityManager->getRepository(User::class)->findOneBy(['id' => $id])) {
            $id = random_int(0, 10000);
        }
        $user->setId($id);
        $hash = bin2hex(random_bytes(18));
        $user->setSession($hash);
        $user->setLastInteraction(new DateTime("now"));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->serializer->serialize(['code' => 0, "message" => "user was created successfully", 'id' => $user->getId(), 'hash' => $user->getSession()], 'json');
    }
    public function deleteUser(mixed $requestBody)
    {

        if (!is_array($requestBody)) {
            return $this->serializer->serialize(['code' => 1, "message" => "request is not in form-data format!"], 'json');
        }

        $hash = $requestBody['hash'];
        $id = $requestBody['id'];
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        if (!$user || $user->getSession() !== $hash) {
            return $this->serializer->serialize(['code' => 1, "message" => "hash does not match with user id (possible account leak)"], 'json');
        }

        $auctions = $this->entityManager->getRepository(Auction::class)->findBy(['user' => $id]);
        foreach ($auctions as $auction) {
            $this->entityManager->remove($auction);
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->serializer->serialize(['code' => 0, "message" => "user deleted successfully"], 'json');
    }
    public function fetchAll()
    {
        $userzy = $this->entityManager->getRepository(User::class)->findAll();
        return $this->serializer->serialize($userzy, "json");
    }
}
