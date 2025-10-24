<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwentiethOrderPromoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->markdown('emails.twentieth-order-promo')
                    ->subject('🎊 VIP Exclusive: 20% Off for Your 20th Order!');
    }
}
