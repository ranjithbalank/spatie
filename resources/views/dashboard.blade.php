<x-app-layout>
    {{-- Dashboard Layout Wrapper --}}

    @section('content')
    {{-- Left Side Card - Welcome Message --}}
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-gray-800 dark:to-gray-900 
            overflow-hidden shadow-lg sm:rounded-2xl transition-all duration-300 hover:shadow-2xl mb-4">
        <div class="p-8 text-center">

            {{-- Emoji / Icon --}}
            <div class="flex justify-center mt-6 mb-6">
                <div class="w-16 h-16 flex items-center justify-center rounded-full bg-blue-600 text-white text-3xl shadow-md">
                    😎
                </div>
            </div>

            {{-- Greeting --}}
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Hello, <span class="text-red-600 dark:text-blue-400">{{ Auth::user()->name }}</span> 👋
            </h2>

            {{-- Subtitle --}}
            <p class="mt-2 text-lg text-gray-700 dark:text-gray-300 mb-4">
                Welcome to your <b>MyDMW Dashboard</b>
            </p>

            {{-- Success note --}}
            <!-- <p class="mt-4 text-sm text-green-700 dark:text-green-400 font-medium bg-green-100 dark:bg-green-800 px-4 py-2 rounded-lg inline-block">
                ✅ You have successfully logged in!
            </p> -->
        </div>
    </div>


    {{-- Right Side Cards (Dynamic Stats) --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-2">
        {{-- Show this card only for admin, manager, or hr --}}
        @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('hr'))
        <div
            class="rounded-xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 bg-white dark:bg-gray-800">
            <a href="{{ route('leaves.index', ['view' => 'team']) }}"
                class="block p-6 hover:bg-gray-50 dark:hover:bg-gray-700">
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-3">
                        Unapproved Leaves
                    </h3>
                    <p class="text-2xl font-extrabold text-red-600 dark:text-red-400">
                        {{ $pendingCount }}
                    </p>
                </div>
            </a>
        </div>
        @endif

        <div
            class="rounded-xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 bg-white dark:bg-gray-800">
            <a href="{{ route('leaves.index', ['view' => 'mine']) }}"
                class="block p-6 hover:bg-gray-50 dark:hover:bg-gray-700">
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-3">
                        My Leave Balance
                    </h3>

                    @if ($user_leave > 10)
                    <p class="text-2xl font-extrabold text-green-600 dark:text-green-400 mb-3">
                        {{ $user_leave }}
                    </p>
                    @elseif ($user_leave > 0 && $user_leave <= 10)
                        <p class="text-2xl font-extrabold text-yellow-500 dark:text-yellow-400 mb-3">
                        {{ $user_leave }}
                        </p>
                        @else
                        <p class="text-2xl font-extrabold text-red-600 dark:text-red-400 mb-3">
                            {{ $user_leave }}
                        </p>
                        @endif

                        {{-- <span class="mt-1 text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2">Days</span> --}}
                </div>
            </a>
        </div>

        {{-- Internal Job Postings Card --}}
        <div
            class=" rounded-xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 bg-white dark:bg-gray-800">
            <a href="{{ route('internal-jobs.index') }}"
                class="block p-6 hover:bg-gray-50 dark:hover:bg-gray-700">
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-3">
                        Internal Job Postings
                    </h3>
                    <p class="text-2xl font-extrabold text-red-600 dark:text-red-400 mb-3">
                        {{ $ijpCount }}
                    </p>
                </div>
            </a>
        </div>
        @hasrole('admin')
        <div
            class=" rounded-xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 bg-white dark:bg-gray-800">
            <a
                class="block p-6 hover:bg-gray-50 dark:hover:bg-gray-700">
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-3">
                        Tickets / Feedbacks
                    </h3>
                    <p class="text-2xl font-extrabold text-red-600 dark:text-red-400 mb-3">
                        {{ $tickets }}
                    </p>
                </div>
            </a>
        </div>
        @endhasrole
    </div>

    {{-- Upcoming Holidays + Circulars --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Upcoming Holidays --}}
        <div
            class="rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 bg-white dark:bg-gray-800" style="height: 200px;">
            <div class="p-6 w-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Upcoming Holidays</h3>
                </div>
                <div class="h-64 overflow-hidden">
                    @if ($holidays->count() > 0)
                    @foreach ($holidays as $holiday)
                    <div class="holiday-item flex items-center p-4 mb-2 bg-gray-50 rounded-lg dark:bg-gray-700 shadow-sm"
                        style="display: none;">
                        <div class="flex-shrink-0 text-center mr-6">
                            <span class="block text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                {{ \Carbon\Carbon::parse($holiday->date)->format('d') }}
                            </span>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($holiday->date)->format('M') }}
                            </span>
                        </div>
                        <div class="px-6">
                            <p class="text-lg font-medium text-red-900 dark:text-red-100">
                                {{ ucfirst($holiday->name) }}
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

        {{-- Upcoming Circulars --}}
        <div
            class="rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 bg-white dark:bg-gray-800" style="height: 200px" ;>
            <div class="p-6 w-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Upcoming Circulars</h3>
                </div>
                <div class="h-64 overflow-hidden">
                    @if ($circulars->count() > 0)
                    @foreach ($circulars as $circular)
                    <div class="circular-item flex items-center p-4 mb-3 bg-gray-50 rounded-lg dark:bg-gray-700 shadow-sm"
                        style="display: none;">
                        {{-- Date --}}
                        <div class="flex-shrink-0 text-center mr-6">
                            <span class="block text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                {{ \Carbon\Carbon::parse($circular->created_at)->format('d') }}
                            </span>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($circular->created_at)->format('M') }}
                            </span>
                        </div>
                        {{-- Details --}}
                        <div class="flex flex-col px-6">
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ ucfirst($circular->circular_name) ?? 'Untitled Circular' }}
                            </p>
                            <button class="mt-1 text-sm text-indigo-600 hover:underline flex items-center gap-1"
                                data-bs-toggle="modal" data-bs-target="#pdfModal{{ $circular->id }}">
                                View Document
                            </button>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <p class="text-gray-500 text-center py-8">No circulars are available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modals for Circulars --}}
    @foreach ($circulars as $circular)
    <div class="modal fade" id="pdfModal{{ $circular->id }}" tabindex="-1"
        aria-labelledby="pdfModalLabel{{ $circular->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Circular - {{ $circular->circular_no }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" style="height: 80vh;">
                    <iframe
                        src="{{ asset('pdfjs/web/viewer.html') }}?file={{ urlencode(asset('storage/' . $circular->file_path)) }}#toolbar=0"
                        width="100%" height="100%" style="border: none;">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
    @endforeach


    @endsection
</x-app-layout>

{{-- Rotation Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function rotateItems(selector, interval = 5000) {
            const items = document.querySelectorAll(selector);
            let currentIndex = 0;
            if (items.length > 0) {
                items[currentIndex].style.display = 'flex';
                setInterval(() => {
                    items[currentIndex].style.display = 'none';
                    currentIndex = (currentIndex + 1) % items.length;
                    items[currentIndex].style.display = 'flex';
                }, interval);
            }
        }

        rotateItems('.holiday-item', 5000);
        rotateItems('.circular-item', 5000);
    });
</script>