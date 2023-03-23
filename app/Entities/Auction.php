<?php

namespace App\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;

class Auction{

    #[Orm\Id]
    #[Orm\Column]
    private int $id;

    #[Orm\Column]
    private string $title;

    #[Orm\Column]
    private string $description;

    #[Orm\Column]
    private float $amount;

}