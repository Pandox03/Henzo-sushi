<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MoroccanPhone implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove all non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $value);
        
        // Moroccan phone number patterns:
        // Mobile: 06xxxxxxxx, 07xxxxxxxx (10 digits starting with 06 or 07)
        // Landline: 05xxxxxxxx (10 digits starting with 05)
        // International: +212xxxxxxxxx (13 digits starting with +212)
        
        $patterns = [
            '/^0[567][0-9]{8}$/', // National format: 06xxxxxxxx, 07xxxxxxxx, 05xxxxxxxx
            '/^\+212[567][0-9]{8}$/', // International format: +212xxxxxxxxx
        ];
        
        $isValid = false;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $phone)) {
                $isValid = true;
                break;
            }
        }
        
        if (!$isValid) {
            $fail('The :attribute must be a valid Moroccan phone number (e.g., 06xxxxxxxx, +212xxxxxxxxx).');
        }
    }
}