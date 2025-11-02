<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default schedule for weekdays (Monday - Friday)
        $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        foreach ($weekdays as $day) {
            Schedule::updateOrCreate(
                ['day_of_week' => $day, 'is_override' => false],
                [
                    'is_closed' => false,
                    'lunch_start' => '11:30:00',
                    'lunch_end' => '15:30:00',
                    'dinner_start' => '18:30:00',
                    'dinner_end' => '23:30:00',
                ]
            );
        }

        // Default schedule for weekends (Saturday - Sunday)
        $weekends = ['saturday', 'sunday'];
        foreach ($weekends as $day) {
            Schedule::updateOrCreate(
                ['day_of_week' => $day, 'is_override' => false],
                [
                    'is_closed' => false,
                    'lunch_start' => '11:30:00',
                    'lunch_end' => null, // Continuous hours for weekends
                    'dinner_start' => null, // Continuous hours for weekends
                    'dinner_end' => '23:30:00',
                ]
            );
        }
    }
}