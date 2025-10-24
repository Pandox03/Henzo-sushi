<?php

namespace App\Observers;

use App\Models\Order;
use App\Mail\FirstOrderPromoMail;
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
            switch ($deliveredOrdersCount) {
                case 1:
                    Mail::to($user->email)->send(new FirstOrderPromoMail());
                    break;
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
