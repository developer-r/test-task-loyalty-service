<?php

namespace App\Http\Requests\LoyaltyPoints;

use App\Rules\TransactionIsActive;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CancelRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'cancellation_reason' => ['required', 'string'],
            'transaction_id' => [
                'required',
                'integer',
                new TransactionIsActive($this->input('transaction_id'))
            ]
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'cancellation_reason.required' => 'Cancellation reason is not specified',
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
