<?php

namespace App\Models;

use App\Mail\AccountActivated;
use App\Mail\AccountDeactivated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * App\Models\LoyaltyAccount
 *
 * @property int $id
 * @property string $phone
 * @property string $card
 * @property string $email
 * @property bool $email_notification
 * @property bool $phone_notification
 * @property bool $active
 */
class LoyaltyAccount extends Model
{
    protected $table = 'loyalty_account';

    protected $fillable = [
        'phone',
        'card',
        'email',
        'email_notification',
        'phone_notification',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'email_notification' => 'boolean',
        'phone_notification' => 'boolean',
    ];

    public function getBalance(): float
    {
        return LoyaltyPointsTransaction::where('canceled', '=', 0)->where('account_id', '=', $this->id)->sum('points_amount');
    }

    public function notify()
    {
        if ($this->email != '' && $this->email_notification) {
            if ($this->active) {
                Mail::to($this)->send(new AccountActivated($this->getBalance()));
            } else {
                Mail::to($this)->send(new AccountDeactivated());
            }
        }

        if ($this->phone != '' && $this->phone_notification) {
            // instead SMS component
            Log::info('Account: phone: ' . $this->phone . ' ' . ($this->active ? 'Activated' : 'Deactivated'));
        }
    }

    public function isEmailNotification(): bool
    {
        return $this->email != '' && $this->email_notification;
    }

    public function isPhoneNotification(): bool
    {
        return $this->phone != '' && $this->phone_notification;
    }
}
