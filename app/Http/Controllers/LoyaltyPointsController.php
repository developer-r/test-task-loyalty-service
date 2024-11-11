<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoyaltyPoints\CancelRequest;
use App\Http\Requests\LoyaltyPoints\DepositRequest;
use App\Http\Requests\LoyaltyPoints\WithdrawRequest;
use App\Http\Resources\Transactions\TransactionResource;
use App\Mail\LoyaltyPointsReceived;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoyaltyPointsController extends Controller
{
    public function deposit(DepositRequest $request): JsonResponse
    {
        $data = $request->validated();

        $type = $data['account_type'];
        $id = $data['account_id'];

        $account = LoyaltyAccount::where($type, '=', $id)->first();
        $transaction =  LoyaltyPointsTransaction::performPaymentLoyaltyPoints($account->id, $data['loyalty_points_rule'], $data['description'], $data['payment_id'], $data['payment_amount'], $data['payment_time']);
        Log::info($transaction);
        if ($account->email != '' && $account->email_notification) {
            Mail::to($account)->send(new LoyaltyPointsReceived($transaction->points_amount, $account->getBalance()));
        }
        if ($account->phone != '' && $account->phone_notification) {
            // instead SMS component
            Log::info('You received' . $transaction->points_amount . 'Your balance' . $account->getBalance());
        }

        return new TransactionResource($transaction);
    }

    public function cancel(CancelRequest $request): JsonResponse
    {
        $data = $request->validated();

        $transaction = LoyaltyPointsTransaction::where('id', '=', $data['transaction_id'])->where('canceled', '=', 0)->first();
        $transaction->canceled = time();
        $transaction->cancellation_reason = $data['cancellation_reason'];
        $transaction->save();

        return response()->json(['message' => 'ok']);
    }

    public function withdraw(WithdrawRequest $request): JsonResponse
    {
        $data = $request->validated();

        $type = $data['account_type'];
        $id = $data['account_id'];

        $account = LoyaltyAccount::where($type, '=', $id)->first();

        if ($data['points_amount'] <= 0) {
            Log::info('Wrong loyalty points amount: ' . $data['points_amount']);
            return response()->json(['message' => 'Wrong loyalty points amount'], 400);
        }

        if ($account->getBalance() < $data['points_amount']) {
            Log::info('Insufficient funds: ' . $data['points_amount']);
            return response()->json(['message' => 'Insufficient funds'], 400);
        }

        $transaction = LoyaltyPointsTransaction::withdrawLoyaltyPoints($account->id, $data['points_amount'], $data['description']);
        Log::info($transaction);

        return new TransactionResource($transaction);
    }
}
