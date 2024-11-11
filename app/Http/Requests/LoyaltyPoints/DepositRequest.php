<?php

namespace App\Http\Requests\LoyaltyPoints;

use App\Models\LoyaltyPointsRule;
use App\Rules\AccountExists;
use App\Rules\AccountIsActive;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DepositRequest extends FormRequest
{
    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        Log::info('Deposit transaction input: ' . print_r($this->all(), true));
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $accountType = $this->input('account_type');

        return [
            'account_type' => ['required', 'string', 'in:phone,card,email'],
            'account_id' => [
                'required',
                'integer',
                'exists:App\Models\LoyaltyAccount,id',
                new AccountExists($accountType),
                new AccountIsActive($accountType),
            ],
            'loyalty_points_rule' => ['required', 'string', 'exists:App\Models\LoyaltyPointsRule,points_rule'],
            'description' => ['required', 'string'],
            'payment_id' => ['required', 'string'],
            'payment_amount' => ['required', 'numeric'],
            'payment_time' => ['required', 'date'],
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'account_type.in' => 'Wrong account parameters',
        ];
    }

    /**
     * @param  Validator  $validator
     * @return void
     */
    protected function failedValidation(Validator $validator): void
    {
        $errorMessage = $validator->errors()->first();

        Log::info($errorMessage);

        throw new ValidationException(
            $validator,
            new JsonResponse(['message' => $errorMessage], 400)
        );
    }
}
