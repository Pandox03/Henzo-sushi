<?php

namespace App\Observers;

use App\Models\Order;
use App\Mail\TenthOrderPromoMail;
use App\Mail\TwentiethOrderPromoMail;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order)
    {
        // Check if status was changed to 'delivered'
        if ($order->isDirty('status') && $order->status === 'delivered') {
            $user = $order->user;
            
            // Get count of delivered orders for this user
            $deliveredOrdersCount = $user->orders()
                ->where('status', 'delivered')
                ->count();
            
            // Send appropriate email based on order count
            // Note: FirstOrderPromoMail (WELCOME code) is sent after OTP verification, not after first order
            switch ($deliveredOrdersCount) {
                case 10:
                    Mail::to($user->email)->send(new TenthOrderPromoMail());
                    break;
                case 20:
                    Mail::to($user->email)->send(new TwentiethOrderPromoMail());
                    break;
            }
        }
    }
}
