<?php

namespace App\Services\LoyaltyPoint\Handlers;

use App\Models\LoyaltyPointsTransaction;
use App\Services\LoyaltyPoint\DTO\CreateWithdrawLoyaltyPointDTO;

class WithdrawLoyaltyPointHandler
{
    public function handler(CreateWithdrawLoyaltyPointDTO $dto) {
        return LoyaltyPointsTransaction::query()
            ->create([
                'account_id' => $dto->accountId,
                'points_rule' => 'withdraw',
                'points_amount' => $dto->pointsAmount,
                'description' => $dto->description,
            ]);
    }
}
