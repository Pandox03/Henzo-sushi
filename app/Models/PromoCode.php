<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'minimum_order_amount',
        'usage_limit_per_user',
        'total_usage_limit',
        'valid_for_days',
        'expires_at',
        'is_active',
        'send_email_to_users',
        'emailed_at',
        'applicable_products',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'expires_at' => 'date',
        'is_active' => 'boolean',
        'send_email_to_users' => 'boolean',
        'emailed_at' => 'datetime',
        'applicable_products' => 'array',
    ];

    /**
     * Boot method to calculate expires_at when valid_for_days is set
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($promoCode) {
            if ($promoCode->valid_for_days && !$promoCode->expires_at) {
                $promoCode->expires_at = Carbon::now()->addDays($promoCode->valid_for_days);
            }
        });
    }

    /**
     * Check if promo code is valid
     */
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->total_usage_limit) {
            $currentUsage = $this->usages()->count();
            if ($currentUsage >= $this->total_usage_limit) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if user can use this promo code
     */
    public function canBeUsedByUser($userId, $orderTotal = 0)
    {
        if (!$this->isValid()) {
            return false;
        }

        // Check minimum order amount
        if ($this->minimum_order_amount && $orderTotal < $this->minimum_order_amount) {
            return false;
        }

        // Check usage limit per user
        $userUsageCount = $this->usages()->where('user_id', $userId)->count();
        if ($userUsageCount >= $this->usage_limit_per_user) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount amount for a given order total
     */
    public function calculateDiscount($orderTotal, $productsInCart = [])
    {
        // Check if there are applicable products
        if ($this->applicable_products && !empty($this->applicable_products)) {
            // Only apply discount to applicable products
            $applicableTotal = 0;
            foreach ($productsInCart as $item) {
                if (in_array($item['product']->id, $this->applicable_products)) {
                    $applicableTotal += $item['total'];
                }
            }
            $orderTotal = $applicableTotal;
        }

        if ($this->discount_type === 'percentage') {
            return round($orderTotal * ($this->discount_value / 100), 2);
        } else {
            // Fixed amount - cap at order total
            return min($this->discount_value, $orderTotal);
        }
    }

    /**
     * Relationships
     */
    public function usages()
    {
        return $this->hasMany(PromoCodeUsage::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, PromoCodeUsage::class, 'promo_code_id', 'id', 'id', 'order_id');
    }
}
