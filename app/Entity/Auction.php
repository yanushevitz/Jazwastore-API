<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table("auctions")]
class Auction{
    #[Id]
    #[Column]
    private int $id;

    #[Column]
    private string $title;

    #[Column]
    private string $description;

    #[Column]
    private float $amount;

    #[Column]
    private int $status;
    
	#[Column]
	private string $subject;

    #[Column]
    private DateTime $date;

    #[Column]
    private int $class;

	#[Column]
	private string $img;

	#[Column]
	#[ManyToOne(targetEntity: User::class, inversedBy: 'auction')]
	private int $user;

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
	public function getTitle(): string {
		return $this->title;
	}
	
	/**
	 * @param string $title 
	 * @return self
	 */
	public function setTitle(string $title): self {
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}
	
	/**
	 * @param string $description 
	 * @return self
	 */
	public function setDescription(string $description): self {
		$this->description = $description;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getAmount(): float {
		return $this->amount;
	}
	
	/**
	 * @param float $amount 
	 * @return self
	 */
	public function setAmount(float $amount): self {
		$this->amount = $amount;
		return $this;
	}

	/**
	 * @return DateTime
	 */
	public function getDate(): DateTime {
		return $this->date;
	}
	
	/**
	 * @param DateTime $date 
	 * @return self
	 */
	public function setDate(DateTime $date): self {
		$this->date = $date;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getClass(): int {
		return $this->class;
	}
	
	/**
	 * @param int $class 
	 * @return self
	 */
	public function setClass(int $class): self {
		$this->class = $class;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSubject(): string {
		return $this->subject;
	}
	
	/**
	 * @param string $subject 
	 * @return self
	 */
	public function setSubject(string $subject): self {
		$this->subject = $subject;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getImg(): string {
		return $this->img;
	}
	
	/**
	 * @param string $img 
	 * @return self
	 */
	public function setImg(string $img): self {
		$this->img = $img;
		return $this;
	}

	/**
	 * @return User
	 */
	public function getUser(): int {
		return $this->user;
	}
	
	/**
	 * @param User $user 
	 * @return self
	 */
	public function setUser(User $user): self {
		$this->user = $user->getId();
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStatus(): int {
		return $this->status;
	}
	
	/**
	 * @param int $status 
	 * @return self
	 */
	public function setStatus(int $status): self {
		$this->status = $status;
		return $this;
	}
}