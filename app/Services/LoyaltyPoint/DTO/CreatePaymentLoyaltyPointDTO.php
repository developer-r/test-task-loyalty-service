<?php

namespace App\Services\LoyaltyPoint\DTO;

class CreatePaymentLoyaltyPointDTO
{
    public function __construct(
        public $accountId,
        public $pointsRule,
        public $description,
        public $paymentId,
        public $paymentAmount,
        public $paymentTime
    ) {}
}
