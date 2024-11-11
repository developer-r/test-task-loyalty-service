<?php

namespace App\Repositories;

use App\Models\LoyaltyPointsRule;

class LoyaltyPointsRuleRepository
{
    public function getByPointRule(string $pointsRule): ?LoyaltyPointsRule
    {
        return LoyaltyPointsRule::query()
            ->where('points_rule', '=', $pointsRule)
            ->first();
    }
}
