<?php

namespace App\Services\LoyaltyRule;

use App\Models\LoyaltyPointsRule;
use App\Services\LoyaltyRule\Rules\AbsoluteLoyaltyPointsRule;
use App\Services\LoyaltyRule\Rules\RelativeLoyaltyPointsRule;
use InvalidArgumentException;

final class LoyaltyRuleFactory
{
    /**
     * @param  string  $type
     * @return AbsoluteLoyaltyPointsRule|RelativeLoyaltyPointsRule
     */
    public static function create(string $type): AbsoluteLoyaltyPointsRule|RelativeLoyaltyPointsRule
    {
        return match ($type) {
            LoyaltyPointsRule::ACCRUAL_TYPE_RELATIVE_RATE => new RelativeLoyaltyPointsRule(),
            LoyaltyPointsRule::ACCRUAL_TYPE_ABSOLUTE_POINTS_AMOUNT => new AbsoluteLoyaltyPointsRule(),

            'default' => throw new InvalidArgumentException('Invalid loyalty points rule type')
        };
    }
}
