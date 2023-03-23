<?php

namespace App\Enum;

enum AuctionStatus{
    case Active;
    case Under_transaction;
    case Inactive;
}