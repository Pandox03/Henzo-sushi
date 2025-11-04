<?php

namespace App\Jobs;

use App\Mail\PromoCodeMail;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPromoCodeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $promoCode;
    public $user;
    
    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = [60, 300, 900]; // Wait 1min, 5min, 15min before retries

    /**
     * Create a new job instance.
     */
    public function __construct(PromoCode $promoCode, User $user)
    {
        $this->promoCode = $promoCode;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->user->email)->send(new PromoCodeMail($this->promoCode, $this->user));
            Log::info('Promo code email sent successfully via queue', [
                'promo_code_id' => $this->promoCode->id,
                'user_id' => $this->user->id,
                'user_email' => $this->user->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send promo code email via queue', [
                'promo_code_id' => $this->promoCode->id,
                'user_id' => $this->user->id,
                'user_email' => $this->user->email,
                'error' => $e->getMessage(),
            ]);
            
            // Re-throw to allow job to be retried
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Promo code email job failed after all retries', [
            'promo_code_id' => $this->promoCode->id,
            'user_id' => $this->user->id,
            'user_email' => $this->user->email,
            'error' => $exception->getMessage(),
        ]);
    }
}