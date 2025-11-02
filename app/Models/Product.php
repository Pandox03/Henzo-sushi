<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'is_available',
        'preparation_time',
        'category_id',
        'has_discount',
        'discount_type',
        'discount_value',
        'discount_expires_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'has_discount' => 'boolean',
        'discount_value' => 'decimal:2',
        'discount_expires_at' => 'date',
    ];

    /**
     * Calculate the discounted price
     */
    public function getDiscountedPriceAttribute()
    {
        if (!$this->has_discount || !$this->isDiscountValid()) {
            return $this->price;
        }

        if ($this->discount_type === 'percentage') {
            $discount = $this->price * ($this->discount_value / 100);
        } else {
            $discount = $this->discount_value;
        }

        return max(0, $this->price - $discount);
    }

    /**
     * Check if discount is valid
     */
    public function isDiscountValid()
    {
        if (!$this->has_discount) {
            return false;
        }

        if ($this->discount_expires_at && $this->discount_expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Get the discount amount
     */
    public function getDiscountAmountAttribute()
    {
        if (!$this->has_discount || !$this->isDiscountValid()) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            return round($this->price * ($this->discount_value / 100), 2);
        } else {
            return min($this->discount_value, $this->price);
        }
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
