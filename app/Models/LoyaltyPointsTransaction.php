<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LoyaltyPointsTransaction
 *
 * @property int $id
 * @property int $account_id
 * @property int $points_rule
 * @property float $points_amount
 * @property string $description
 * @property string $payment_id
 * @property float $payment_amount
 * @property int $payment_time
 */
class LoyaltyPointsTransaction extends Model
{
    protected $table = 'loyalty_points_transaction';

    protected $fillable = [
        'account_id',
        'points_rule',
        'points_amount',
        'description',
        'payment_id',
        'payment_amount',
        'payment_time',
    ];
}
