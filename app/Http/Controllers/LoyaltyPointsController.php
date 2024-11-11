<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoyaltyPoints\CancelRequest;
use App\Http\Requests\LoyaltyPoints\DepositRequest;
use App\Http\Requests\LoyaltyPoints\WithdrawRequest;
use App\Http\Resources\Transactions\TransactionResource;
use App\Services\LoyaltyPoint\LoyaltyPointService;
use HttpResponseException;
use Illuminate\Http\JsonResponse;

class LoyaltyPointsController extends Controller
{
    /**
     * @param  LoyaltyPointService  $loyaltyPointService
     */
    public function __construct(private LoyaltyPointService $loyaltyPointService)
    {}

    /**
     * @param  DepositRequest  $request
     * @return JsonResponse
     */
    public function deposit(DepositRequest $request): JsonResponse
    {
        $data = $request->validated();

        $transaction = $this->loyaltyPointService->deposit($data);

        return new TransactionResource($transaction);
    }

    /**
     * @param  CancelRequest  $request
     * @return JsonResponse
     */
    public function cancel(CancelRequest $request): JsonResponse
    {
        $data = $request->validated();

        $this->loyaltyPointService->cancel($data);

        return response()->json(['message' => 'ok']);
    }

    /**
     * @param  WithdrawRequest  $request
     * @return JsonResponse
     * @throws HttpResponseException
     */
    public function withdraw(WithdrawRequest $request): JsonResponse
    {
        $data = $request->validated();

        $transaction = $this->loyaltyPointService->withdraw($data);

        return new TransactionResource($transaction);
    }
}
