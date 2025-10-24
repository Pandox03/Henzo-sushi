<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TenthOrderPromoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->markdown('emails.tenth-order-promo')
                    ->subject('🎉 Special Reward for Your 10th Order!');
    }
}
