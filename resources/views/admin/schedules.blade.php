@php
    use Carbon\Carbon;
@endphp

<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">📅 Schedule Management</h1>
                <p class="mt-2 text-gray-600">Manage restaurant operating hours and special day schedules</p>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Default Weekly Schedule -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">📆 Default Weekly Schedule</h2>
                        <p class="mt-1 text-sm text-gray-600">Set standard operating hours for each day of the week</p>
                    </div>
                    <div class="p-6 space-y-6">
                        @foreach($schedules as $schedule)
                            <div class="border rounded-lg p-4">
                                <form method="POST" action="{{ route('admin.schedules.update') }}" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="day_of_week" value="{{ $schedule->day_of_week }}">
                                    
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="text-lg font-semibold capitalize">{{ $schedule->day_of_week }}</h3>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_closed" value="1" {{ $schedule->is_closed ? 'checked' : '' }}
                                                onchange="this.form.submit()" class="mr-2">
                                            <span class="text-sm text-gray-600">Closed</span>
                                        </label>
                                    </div>

                                    @if(!$schedule->is_closed)
                                        @php
                                            $isWeekend = in_array($schedule->day_of_week, ['saturday', 'sunday']);
                                        @endphp
                                        
                                        @if($isWeekend)
                                            <!-- Weekend: Continuous Hours -->
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Opening Time</label>
                                                    <input type="time" name="lunch_start" value="{{ $schedule->lunch_start ? Carbon::parse($schedule->lunch_start)->format('H:i') : '11:30' }}"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Closing Time</label>
                                                    <input type="time" name="dinner_end" value="{{ $schedule->dinner_end ? Carbon::parse($schedule->dinner_end)->format('H:i') : '23:30' }}"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                </div>
                                            </div>
                                        @else
                                            <!-- Weekday: Split Hours -->
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Lunch Hours</label>
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs text-gray-500 mb-1">Start</label>
                                                            <input type="time" name="lunch_start" value="{{ $schedule->lunch_start ? Carbon::parse($schedule->lunch_start)->format('H:i') : '11:30' }}"
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs text-gray-500 mb-1">End</label>
                                                            <input type="time" name="lunch_end" value="{{ $schedule->lunch_end ? Carbon::parse($schedule->lunch_end)->format('H:i') : '15:30' }}"
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Dinner Hours</label>
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs text-gray-500 mb-1">Start</label>
                                                            <input type="time" name="dinner_start" value="{{ $schedule->dinner_start ? Carbon::parse($schedule->dinner_start)->format('H:i') : '18:30' }}"
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs text-gray-500 mb-1">End</label>
                                                            <input type="time" name="dinner_end" value="{{ $schedule->dinner_end ? Carbon::parse($schedule->dinner_end)->format('H:i') : '23:30' }}"
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                    <button type="submit" class="w-full mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                                        Update Schedule
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Special Dates / Override Schedules -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">🎉 Special Dates / Overrides</h2>
                        <p class="mt-1 text-sm text-gray-600">Create custom schedules for holidays or special events</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Form to Create New Override -->
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                            <h3 class="text-lg font-semibold mb-4">Create New Override</h3>
                            <form method="POST" action="{{ route('admin.schedules.override') }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                    <input type="date" name="override_date" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_closed" value="1" id="override_closed" class="mr-2">
                                    <label for="override_closed" class="text-sm text-gray-700">Closed on this date</label>
                                </div>
                                <div id="override-hours" class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Lunch Hours</label>
                                        <div class="grid grid-cols-2 gap-4">
                                            <input type="time" name="lunch_start" value="11:30"
                                                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            <input type="time" name="lunch_end" value="15:30"
                                                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Dinner Hours</label>
                                        <div class="grid grid-cols-2 gap-4">
                                            <input type="time" name="dinner_start" value="18:30"
                                                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            <input type="time" name="dinner_end" value="23:30"
                                                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                                        <textarea name="notes" rows="2" placeholder="e.g., Holiday, Special Event"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">
                                    Create Override
                                </button>
                            </form>
                        </div>

                        <!-- Existing Overrides -->
                        @if($overrideSchedules->count() > 0)
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold">Existing Overrides</h3>
                                @foreach($overrideSchedules as $override)
                                    <div class="border rounded-lg p-4 bg-gray-50">
                                        <div class="flex items-center justify-between mb-3">
                                            <div>
                                                <h4 class="font-semibold">{{ $override->override_date->format('F d, Y') }}</h4>
                                                @if($override->notes)
                                                    <p class="text-sm text-gray-600">{{ $override->notes }}</p>
                                                @endif
                                            </div>
                                            <form method="POST" action="{{ route('admin.schedules.delete', $override) }}" onsubmit="return confirm('Delete this override schedule?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Delete</button>
                                            </form>
                                        </div>
                                        @if($override->is_closed)
                                            <p class="text-red-600 font-semibold">Closed</p>
                                        @else
                                            <p class="text-sm text-gray-700">
                                                @if($override->lunch_start && $override->lunch_end)
                                                    Lunch: {{ Carbon::parse($override->lunch_start)->format('g:i A') }} - {{ Carbon::parse($override->lunch_end)->format('g:i A') }}
                                                @endif
                                                @if($override->dinner_start && $override->dinner_end)
                                                    @if($override->lunch_start && $override->lunch_end) | @endif
                                                    Dinner: {{ Carbon::parse($override->dinner_start)->format('g:i A') }} - {{ Carbon::parse($override->dinner_end)->format('g:i A') }}
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm text-center py-8">No override schedules created yet</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle override hours when closed checkbox is checked
        document.getElementById('override_closed')?.addEventListener('change', function() {
            document.getElementById('override-hours').style.display = this.checked ? 'none' : 'block';
        });
    </script>
</x-admin-layout>
