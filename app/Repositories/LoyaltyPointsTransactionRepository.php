<?php

namespace App\Repositories;

use App\Models\LoyaltyPointsTransaction;

class LoyaltyPointsTransactionRepository
{
    public function getActiveTransactionById(int $transactionId): ?LoyaltyPointsTransaction
    {
        return LoyaltyPointsTransaction::query()
            ->where('id', '=', $transactionId)
            ->where('canceled', '=', 0)
            ->first();
    }
}
