@php
    use Carbon\Carbon;
@endphp

@extends('layouts.dashboard')

@section('sidebar-nav')
    @include('admin.partials.sidebar-nav', ['active' => 'schedules'])
@endsection

@section('content')
@include('admin.partials.form-styles')

<style>
    .schedule-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .day-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .day-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        text-transform: capitalize;
    }

    .time-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .time-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">📅 Schedule Management</h1>
    <p style="color: var(--text-secondary);">Manage restaurant operating hours and special day schedules</p>
</div>

@if(session('success'))
    <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e; color: #22c55e; padding: 1rem; border-radius: 10px; margin-bottom: 2rem;">
        {{ session('success') }}
    </div>
@endif

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 2rem;">
    @foreach($schedules ?? [] as $schedule)
        <div class="schedule-card">
            <form method="POST" action="{{ route('admin.schedules.update') }}">
                @csrf
                <input type="hidden" name="day_of_week" value="{{ $schedule->day_of_week }}">
                
                <div class="day-header">
                    <div class="day-title">{{ $schedule->day_of_week }}</div>
                    <label class="form-checkbox" style="margin: 0;">
                        <input type="checkbox" name="is_closed" value="1" 
                               {{ $schedule->is_closed ? 'checked' : '' }}
                               onchange="this.form.submit()">
                        <span>Closed</span>
                    </label>
                </div>

                @if(!$schedule->is_closed)
                    @php
                        $isWeekend = in_array($schedule->day_of_week, ['saturday', 'sunday']);
                    @endphp
                    
                    @if($isWeekend)
                        <!-- Weekend: Continuous Hours -->
                        <div class="time-grid">
                            <div class="form-group">
                                <label class="form-label">Opening Time</label>
                                <input type="time" name="lunch_start" 
                                       value="{{ $schedule->lunch_start ? Carbon::parse($schedule->lunch_start)->format('H:i') : '11:30' }}"
                                       class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Closing Time</label>
                                <input type="time" name="dinner_end" 
                                       value="{{ $schedule->dinner_end ? Carbon::parse($schedule->dinner_end)->format('H:i') : '23:00' }}"
                                       class="form-input">
                            </div>
                        </div>
                    @else
                        <!-- Weekday: Split Schedule -->
                        <div class="form-section">
                            <h4 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 1rem;">Lunch Service</h4>
                            <div class="time-grid">
                                <div class="form-group">
                                    <label class="form-label">Start</label>
                                    <input type="time" name="lunch_start" 
                                           value="{{ $schedule->lunch_start ? Carbon::parse($schedule->lunch_start)->format('H:i') : '11:30' }}"
                                           class="form-input">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">End</label>
                                    <input type="time" name="lunch_end" 
                                           value="{{ $schedule->lunch_end ? Carbon::parse($schedule->lunch_end)->format('H:i') : '15:00' }}"
                                           class="form-input">
                                </div>
                            </div>
                        </div>

                        <div class="form-section" style="margin-top: 1.5rem;">
                            <h4 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 1rem;">Dinner Service</h4>
                            <div class="time-grid">
                                <div class="form-group">
                                    <label class="form-label">Start</label>
                                    <input type="time" name="dinner_start" 
                                           value="{{ $schedule->dinner_start ? Carbon::parse($schedule->dinner_start)->format('H:i') : '18:30' }}"
                                           class="form-input">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">End</label>
                                    <input type="time" name="dinner_end" 
                                           value="{{ $schedule->dinner_end ? Carbon::parse($schedule->dinner_end)->format('H:i') : '23:00' }}"
                                           class="form-input">
                                </div>
                            </div>
                        </div>
                    @endif

                    <div style="margin-top: 1.5rem;">
                        <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">
                            Save Schedule
                        </button>
                    </div>
                @endif
            </form>
        </div>
    @endforeach
</div>

@if(($schedules ?? [])->isEmpty())
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 3rem; text-align: center; color: var(--text-secondary);">
        No schedules configured. Please seed the database with default schedules.
    </div>
@endif
@endsection

