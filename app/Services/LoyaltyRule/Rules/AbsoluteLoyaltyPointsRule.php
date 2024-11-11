<?php

namespace App\Services\LoyaltyRule\Rules;

use App\Services\LoyaltyRule\LoyaltyPointsRuleInterface;

class AbsoluteLoyaltyPointsRule implements LoyaltyPointsRuleInterface
{
    public function calculatePoints(float $accrualValue, ?float $paymentAmount = null): float
    {
        return $accrualValue;
    }
}
