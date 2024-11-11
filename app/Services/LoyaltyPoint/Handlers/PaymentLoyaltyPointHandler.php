<?php

namespace App\Services\LoyaltyPoint\Handlers;

use App\Models\LoyaltyPointsTransaction;
use App\Repositories\LoyaltyPointsRuleRepository;
use App\Services\LoyaltyPoint\DTO\CreatePaymentLoyaltyPointDTO;
use App\Services\LoyaltyRule\LoyaltyRuleFactory;

class PaymentLoyaltyPointHandler
{
    public function __construct(private LoyaltyPointsRuleRepository $loyaltyPointsRuleRepository)
    {}

    public function handle(CreatePaymentLoyaltyPointDTO $dto): LoyaltyPointsTransaction
    {
        $pointsAmount = 0;

        if ($pointsRule = $this->loyaltyPointsRuleRepository->getByPointRule($dto->pointsRule)) {
            $rule = LoyaltyRuleFactory::create($pointsRule->accrual_type);

            $pointsAmount = $rule->calculatePoints($pointsRule->accrual_value, $dto->paymentAmount);
        }

        return LoyaltyPointsTransaction::query()
            ->create([
                'account_id' => $dto->accountId,
                'points_rule' => $pointsRule?->id,
                'points_amount' => $pointsAmount,
                'description' => $dto->description,
                'payment_id' => $dto->paymentId,
                'payment_amount' => $dto->paymentAmount,
                'payment_time' => $dto->paymentTime,
            ]);
    }
}
