<?php

namespace App\Rules;

use App\Models\LoyaltyAccount;
use App\Repositories\LoyaltyAccountRepository;
use Illuminate\Contracts\Validation\Rule;

class AccountExists implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(private string $accountType, private LoyaltyAccountRepository $loyaltyAccountRepository)
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
        return $this->loyaltyAccountRepository->existByTypeAndId($this->accountType, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Account is not found';
    }
}
