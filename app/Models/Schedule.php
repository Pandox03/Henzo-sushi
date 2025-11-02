<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_of_week',
        'is_override',
        'is_closed',
        'lunch_start',
        'lunch_end',
        'dinner_start',
        'dinner_end',
        'override_date',
        'notes',
    ];

    protected $casts = [
        'is_override' => 'boolean',
        'is_closed' => 'boolean',
        'override_date' => 'date',
        // Time fields are stored as TIME in database and returned as strings
    ];

    /**
     * Get schedule for a specific day and date
     */
    public static function getScheduleForDate(Carbon $date)
    {
        $dayOfWeek = strtolower($date->format('l')); // monday, tuesday, etc.
        
        // First check for override schedule for this specific date
        $override = self::where('override_date', $date->format('Y-m-d'))
            ->where('is_override', true)
            ->first();
        
        if ($override) {
            return $override;
        }
        
        // Otherwise get default schedule for this day of week
        return self::where('day_of_week', $dayOfWeek)
            ->where('is_override', false)
            ->first();
    }

    /**
     * Check if restaurant is open right now
     */
    public static function isOpenNow()
    {
        $now = Carbon::now();
        $schedule = self::getScheduleForDate($now);
        
        if (!$schedule || $schedule->is_closed) {
            return false;
        }
        
        $currentTime = $now->format('H:i:s');
        
        $dayOfWeek = strtolower($now->format('l'));
        $isWeekend = in_array($dayOfWeek, ['saturday', 'sunday']);
        
        // For weekends, check continuous hours (lunch_start to dinner_end)
        if ($isWeekend && $schedule->lunch_start && $schedule->dinner_end) {
            $start = $schedule->lunch_start; // Already a time string (H:i:s)
            $end = $schedule->dinner_end; // Already a time string (H:i:s)
            
            if ($currentTime >= $start && $currentTime <= $end) {
                return true;
            }
        }
        
        // For weekdays, check split hours
        if (!$isWeekend) {
            // Check if current time is within lunch hours
            if ($schedule->lunch_start && $schedule->lunch_end) {
                $lunchStart = $schedule->lunch_start; // Already a time string
                $lunchEnd = $schedule->lunch_end; // Already a time string
                
                if ($currentTime >= $lunchStart && $currentTime <= $lunchEnd) {
                    return true;
                }
            }
            
            // Check if current time is within dinner hours
            if ($schedule->dinner_start && $schedule->dinner_end) {
                $dinnerStart = $schedule->dinner_start; // Already a time string
                $dinnerEnd = $schedule->dinner_end; // Already a time string
                
                if ($currentTime >= $dinnerStart && $currentTime <= $dinnerEnd) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Get next opening time
     */
    public static function getNextOpeningTime()
    {
        $now = Carbon::now();
        
        // Check next 7 days
        for ($i = 0; $i < 7; $i++) {
            $date = $now->copy()->addDays($i);
            $schedule = self::getScheduleForDate($date);
            
            if (!$schedule || $schedule->is_closed) {
                continue;
            }
            
            $dayOfWeek = strtolower($date->format('l'));
            
            // For weekends, check continuous hours
            if (in_array($dayOfWeek, ['saturday', 'sunday']) && $schedule->lunch_start) {
                $openingTime = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->lunch_start);
                if ($openingTime > $now) {
                    return $openingTime;
                }
            }
            
            // Check lunch hours
            if ($schedule->lunch_start) {
                $lunchStart = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->lunch_start);
                if ($lunchStart > $now) {
                    return $lunchStart;
                }
            }
            
            // Check dinner hours
            if ($schedule->dinner_start) {
                $dinnerStart = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->dinner_start);
                if ($dinnerStart > $now) {
                    return $dinnerStart;
                }
            }
        }
        
        return null;
    }

    /**
     * Get formatted hours for display
     */
    public function getFormattedHours()
    {
        if ($this->is_closed) {
            return 'Closed';
        }
        
        $dayOfWeek = strtolower(Carbon::now()->format('l'));
        $isWeekend = in_array($dayOfWeek, ['saturday', 'sunday']);
        
        // For weekends or continuous hours
        if ($isWeekend || !$this->dinner_start) {
            if ($this->lunch_start && $this->dinner_end) {
                return Carbon::parse($this->lunch_start)->format('g:i A') . ' - ' . Carbon::parse($this->dinner_end)->format('g:i A');
            }
        }
        
        // For weekdays with split hours
        $hours = [];
        if ($this->lunch_start && $this->lunch_end) {
            $hours[] = Carbon::parse($this->lunch_start)->format('g:i A') . ' - ' . Carbon::parse($this->lunch_end)->format('g:i A');
        }
        if ($this->dinner_start && $this->dinner_end) {
            $hours[] = Carbon::parse($this->dinner_start)->format('g:i A') . ' - ' . Carbon::parse($this->dinner_end)->format('g:i A');
        }
        
        return implode(', ', $hours);
    }
}