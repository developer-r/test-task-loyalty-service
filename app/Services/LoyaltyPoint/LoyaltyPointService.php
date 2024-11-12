<?php

namespace App\Services\LoyaltyPoint;

use App\Jobs\LoyaltyPoint\SendEmailJob;
use App\Jobs\LoyaltyPoint\SendSmsJob;
use App\Models\LoyaltyPointsTransaction;
use App\Repositories\LoyaltyAccountRepository;
use App\Repositories\LoyaltyPointsTransactionRepository;
use App\Services\LoyaltyPoint\DTO\CreatePaymentLoyaltyPointDTO;
use App\Services\LoyaltyPoint\Handlers\PaymentLoyaltyPointHandler;
use App\Services\LoyaltyPoint\Handlers\WithdrawLoyaltyPointHandler;
use HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoyaltyPointService
{
    public function __construct(
        private LoyaltyAccountRepository $loyaltyAccountRepository,
        private LoyaltyPointsTransactionRepository $loyaltyPointsTransactionRepository,
        private PaymentLoyaltyPointHandler $paymentLoyaltyPointHandler,
        private WithdrawLoyaltyPointHandler $withdrawLoyaltyPointHandler,
    ) {}

    /**
     * @param  array  $data
     * @return LoyaltyPointsTransaction
     */
    public function deposit(array $data): LoyaltyPointsTransaction
    {
        return DB::transaction(function () use ($data) {
            $type = $data['account_type'];
            $id = $data['account_id'];

            $account = $this->loyaltyAccountRepository->getByTypeAndId($type, $id);

            $dto = new CreatePaymentLoyaltyPointDTO(
                $account->id,
                $data['loyalty_points_rule'],
                $data['description'],
                $data['payment_id'],
                $data['payment_amount'],
                $data['payment_time']
            );

            $transaction = $this->paymentLoyaltyPointHandler->handle($dto);
            Log::info($transaction);

            if ($account->isEmailNotification()) {
                SendEmailJob::dispatch($account, $transaction);
            }

            if ($account->isPhoneNotification()) {
                SendSmsJob::dispatch($account, $transaction);
            }

            return $transaction;
        });
    }

    /**
     * @param  array  $data
     * @return void
     */
    public function cancel(array $data): void
    {
        $transaction = $this->loyaltyPointsTransactionRepository->getActiveTransactionById($data['transaction_id']);

        $transaction->update([
            'canceled' => time(),
            'cancellation_reason' => $data['cancellation_reason'],
        ]);
    }

    /**
     * @param  array  $data
     * @return LoyaltyPointsTransaction
     * @throws HttpResponseException
     */
    public function withdraw(array $data): LoyaltyPointsTransaction
    {
        return DB::transaction(function () use ($data) {
            $type = $data['account_type'];
            $id = $data['account_id'];

            $account = $this->loyaltyAccountRepository->getByTypeAndId($type, $id);

            if ($data['points_amount'] <= 0) {
                Log::info('Wrong loyalty points amount: ' . $data['points_amount']);

                throw new HttpResponseException(response()->json([
                    'message' => 'Wrong loyalty points amount',
                ], 400));
            }

            if ($account->getBalance() < $data['points_amount']) {
                Log::info('Insufficient funds: ' . $data['points_amount']);

                throw new HttpResponseException(response()->json([
                    'message' => 'Insufficient funds',
                ], 400));
            }

            $dto = new CreateWithdrawLoyaltyPointDTO(
                $account->id,
                $data['points_amount'],
                $data['description']
            );

            $transaction = $this->withdrawLoyaltyPointHandler->handler($dto);
            Log::info($transaction);

            return $transaction;
        });
    }
}
