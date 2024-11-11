<?php

namespace App\Rules;

use App\Models\LoyaltyPointsTransaction;
use Illuminate\Contracts\Validation\Rule;

class TransactionIsActive implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(private int $transactionId)
    {}

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return LoyaltyPointsTransaction::query()
            ->where('id', '=', $this->transactionId)
            ->where('canceled', '=', 0)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Transaction is not found.';
    }
}
