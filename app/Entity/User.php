<?php

namespace App\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table("users")]
class User{

    #[Id]
    #[Column]
    private int $id;

    #[Column]
    private string $username;

    #[Column]
    private string $email;

    #[Column]
    private string $facebook;

    #[Column]
    private int $number;

	#[Column(nullable: true)]
	#[OneToMany(Auction::class, 'auctions')]
	private array $auctions;

	#[Column]
	private string $auth0;
    


	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @param int $id 
	 * @return self
	 */
	public function setId(int $id): self {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string {
		return $this->username;
	}
	
	/**
	 * @param string $username 
	 * @return self
	 */
	public function setUsername(string $username): self {
		$this->username = $username;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string {
		return $this->email;
	}
	
	/**
	 * @param string $email 
	 * @return self
	 */
	public function setEmail(string $email): self {
		$this->email = $email;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFacebook(): string {
		return $this->facebook;
	}
	
	/**
	 * @param string $facebook 
	 * @return self
	 */
	public function setFacebook(string $facebook): self {
		$this->facebook = $facebook;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getNumber(): int {
		return $this->number;
	}
	
	/**
	 * @param int $number 
	 * @return self
	 */
	public function setNumber(int $number): self {
		$this->number = $number;
		return $this;
	}

	/**
	 * @return Auction
	 */
	public function getAuction(): array {
		return $this->auctions;
	}
	
	/**
	 * @param Auction $auction 
	 * @return self
	 */
	public function setAuction(Auction $auction): self {
		$this->auctions += $auction;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuth0(): string {
		return $this->auth0;
	}
	
	/**
	 * @param string $auth0 
	 * @return self
	 */
	public function setAuth0(string $auth0): self {
		$this->auth0 = $auth0;
		return $this;
	}
}