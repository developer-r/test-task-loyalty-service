<?php

namespace App\Services\LoyaltyPoint\DTO;

class CreateWithdrawLoyaltyPointDTO
{
    public function __construct(
        public $accountId,
        public $pointsAmount,
        public $description
    ) {
        $this->pointsAmount = -$pointsAmount;
    }
}
