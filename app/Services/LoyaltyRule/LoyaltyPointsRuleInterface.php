<?php

namespace App\Services\LoyaltyRule;

interface LoyaltyPointsRuleInterface
{
    public function calculatePoints(float $accrualValue, ?float $paymentAmount = null): float;
}
