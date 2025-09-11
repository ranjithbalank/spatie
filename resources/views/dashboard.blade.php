<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}
    @section('content')
        {{-- Left Side Card --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-red-900">
                Hello! <span class="text-success"><b>{{ Auth::user()->name }} 😎</b></span>,
                <br><br>
                Welcome to your <b>MyDMW dashboard!</b>
                <br><br>
                {{ __('You have Successfully logged in!') }}
            </div>
        </div>

        {{-- Right Side Cards --}}
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-2">
            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('hr'))
                <div class="rounded-xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300">
                    <a href="{{ route('leaves.index', ['view' => 'team']) }}"
                        class="block p-6 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <div class="text-center">
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2">Unapproved Leaves</h3>
                            <p class="text-xl font-extrabold text-red-600 dark:text-red-400">
                                {{ $pendingCount }}
                            </p>
                        </div>
                    </a>
                </div>
            @endif
            <div class="rounded-xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300">
                <a href="{{ route('internal-jobs.index') }}"
                    class="block p-6 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="text-center">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2">Internal Job Postings</h3>
                        <p class="text-xl font-extrabold text-red-600 dark:text-red-400">
                            {{ $ijpCount }}
                        </p>
                    </div>
                </a>
            </div>
        </div>
        <div class="rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 mt-6 w-full md:w-1/3">
            <div class="p-6 bg-white dark:bg-gray-800 w-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Upcoming Holidays</h3>
                </div>

                <div class="h-64 overflow-hidden">
                    @if ($holidays->count() > 0)
                        @foreach ($holidays as $holiday)
                            <div class="holiday-item flex items-center p-4 mb-2 bg-gray-50 rounded-lg dark:bg-gray-700"
                                style="display: none;">
                                <div class="flex-shrink-0 text-center mr-4">
                                    <span class="block text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ \Carbon\Carbon::parse($holiday->date)->format('d') }}
                                    </span>
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($holiday->date)->format('M') }}
                                    </span>
                                </div>
                                <div class="px-6">
                                    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $holiday->name }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center py-8">No holidays are scheduled for this period.</p>
                    @endif
                </div>
            </div>
        </div>
    @endsection
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const holidayItems = document.querySelectorAll('.holiday-item');
        let currentIndex = 0;

        // If there are holidays, set up the display logic
        if (holidayItems.length > 0) {
            // Initially show the first item
            holidayItems[currentIndex].style.display = 'flex';

            // Function to show the next holiday
            function showNextHoliday() {
                // Hide the current holiday
                holidayItems[currentIndex].style.display = 'none';

                // Move to the next index, or loop back to the start
                currentIndex = (currentIndex + 1) % holidayItems.length;

                // Show the next holiday
                holidayItems[currentIndex].style.display = 'flex';
            }

            // Set an interval to transition to the next holiday every 5 seconds
            setInterval(showNextHoliday, 5000); // 5000ms = 5 seconds
        }
    });
</script>
