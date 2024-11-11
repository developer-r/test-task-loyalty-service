<?php

namespace App\Rules;

use App\Models\LoyaltyAccount;
use Illuminate\Contracts\Validation\Rule;

class AccountIsActive implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(private string $accountType)
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
        $loyaltyAccount = LoyaltyAccount::query()
            ->where($this->accountType, $value)
            ->first();

        return (bool) $loyaltyAccount?->active;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Account is not active';
    }
}
