<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table("users")]
class User
{

	#[Id]
	#[Column]
	private int $id;

	#[Column]
	private string $username;
	#[Column]
	private string $password;

	#[Column(nullable: true)]
	private DateTime $lastInteraction;

	#[Column(nullable: true)]
	private string $email;
	#[Column(nullable: true)]
	private ?string $session;

	#[Column(nullable: true)]
	private string $facebook;

	#[Column(nullable: true)]
	private int $number;

	#[Column(nullable: true)]
	#[OneToMany(Auction::class, 'auctions')]
	private array $auctions;

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id 
	 * @return self
	 */
	public function setId(int $id): self
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * @param string $username 
	 * @return self
	 */
	public function setUsername(string $username): self
	{
		$this->username = $username;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email 
	 * @return self
	 */
	public function setEmail(string $email): self
	{
		$this->email = $email;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFacebook(): string
	{
		return $this->facebook;
	}

	/**
	 * @param string $facebook 
	 * @return self
	 */
	public function setFacebook(string $facebook): self
	{
		$this->facebook = $facebook;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getNumber(): int
	{
		return $this->number;
	}

	/**
	 * @param int $number 
	 * @return self
	 */
	public function setNumber(int $number): self
	{
		$this->number = $number;
		return $this;
	}

	/**
	 * @return Auction
	 */
	public function getAuction(): array
	{
		return $this->auctions;
	}

	/**
	 * @param Auction $auction 
	 * @return self
	 */
	public function setAuction(Auction $auction): self
	{
		$this->auctions += $auction;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $password 
	 * @return self
	 */
	public function setPassword(string $password): self
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSession(): ?string
	{
		return $this->session;
	}

	/**
	 * @param string $session 
	 * @return self
	 */
	public function setSession(?string $session): self
	{
		$this->session = $session;
		return $this;
	}

	/**
	 * @return DateTime
	 */
	public function getLastInteraction(): DateTime
	{
		return $this->lastInteraction;
	}

	/**
	 * @param DateTime $lastInteraction 
	 * @return self
	 */
	public function setLastInteraction(?DateTime $lastInteraction): self
	{
		$this->lastInteraction = $lastInteraction;
		return $this;
	}
}