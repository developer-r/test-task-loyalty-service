<?php

namespace App\Services\LoyaltyRule\Rules;

use App\Services\LoyaltyRule\LoyaltyPointsRuleInterface;

class RelativeLoyaltyPointsRule implements LoyaltyPointsRuleInterface
{
    public function calculatePoints(float $accrualValue, ?float $paymentAmount = null): float
    {
        return ($paymentAmount / 100) * $accrualValue;
    }
}
