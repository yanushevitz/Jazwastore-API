<?php

namespace App\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
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
}