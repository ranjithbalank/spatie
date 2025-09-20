<x-app-layout>
    {{-- Dashboard Layout Wrapper --}}
    @section('content')
    {{-- Left Side Card - Welcome Message --}}
    <div class="overflow-hidden shadow-lg sm:rounded-2xl transition-all duration-300 hover:shadow-2xl mb-4">
        <div class="p-8 text-center">

            {{-- Emoji / Icon --}}
            <div class="flex justify-center mt-6 mb-6">
                <div class="w-16 h-16 flex items-center justify-center rounded-full bg-blue-600 text-white text-3xl shadow-md">
                    😎
                </div>
            </div>

            {{-- Greeting --}}
            <h2 class="text-2xl font-bold text-gray-900">
                Hello, <span class="text-red-600">{{ Auth::user()->name }}</span> 👋
            </h2>

            {{-- Subtitle --}}
            <p class="mt-2 text-lg text-gray-700 mb-4">
                Welcome to your <b>MyDMW Dashboard</b>
            </p>
        </div>
    </div>

    {{-- Right Side Cards (Dynamic Stats) --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-2">
        {{-- Show this card only for admin, manager, or hr --}}
        @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('hr'))
        <div class="rounded-xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 bg-white">
            <a href="{{ route('leaves.index', ['view' => 'team']) }}" class="block p-6 hover:bg-gray-50">
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">
                        Unapproved Leaves
                    </h3>
                    <p class="text-2xl font-extrabold text-red-600">
                        {{ $pendingCount }}
                    </p>
                </div>
            </a>
        </div>
        @endif

        <div class="rounded-xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 bg-white">
            <a href="{{ route('leaves.index', ['view' => 'mine']) }}" class="block p-6 hover:bg-gray-50">
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">
                        My Leave Balance
                    </h3>

                    @if ($user_leave > 10)
                    <p class="text-2xl font-extrabold text-green-600 mb-3">
                        {{ $user_leave }}
                    </p>
                    @elseif ($user_leave > 0 && $user_leave <= 10)
                        <p class="text-2xl font-extrabold text-yellow-500 mb-3">
                        {{ $user_leave }}
                        </p>
                        @else
                        <p class="text-2xl font-extrabold text-red-600 mb-3">
                            {{ $user_leave }}
                        </p>
                        @endif
                </div>
            </a>
        </div>

        {{-- Internal Job Postings Card --}}
        <div class="rounded-xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 bg-white">
            <a href="{{ route('internal-jobs.index') }}" class="block p-6 hover:bg-gray-50">
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">
                        Internal Job Postings
                    </h3>
                    <p class="text-2xl font-extrabold text-red-600 mb-3">
                        {{ $ijpCount }}
                    </p>
                </div>
            </a>
        </div>

        @hasrole('admin')
        <div class="rounded-xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 bg-white">
            <a class="block p-6 hover:bg-gray-50">
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">
                        Tickets / Feedbacks
                    </h3>
                    <p class="text-2xl font-extrabold text-red-600 mb-3">
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
        <div class="rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 bg-white" style="height: 200px;">
            <div class="p-6 w-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Upcoming Holidays</h3>
                </div>
                <div class="h-64 overflow-hidden">
                    @if ($holidays->count() > 0)
                    @foreach ($holidays as $holiday)
                    <div class="holiday-item flex items-center p-4 mb-2 bg-gray-50 rounded-lg shadow-sm" style="display: none;">
                        <div class="flex-shrink-0 text-center mr-6">
                            <span class="block text-2xl font-bold text-indigo-600">
                                {{ \Carbon\Carbon::parse($holiday->date)->format('d') }}
                            </span>
                            <span class="block text-sm font-medium text-gray-500">
                                {{ \Carbon\Carbon::parse($holiday->date)->format('M') }}
                            </span>
                        </div>
                        <div class="px-6">
                            <p class="text-lg font-medium text-red-900">
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
        <div class="rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 bg-white" style="height: 200px;">
            <div class="p-6 w-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Upcoming Circulars</h3>
                </div>
                <div class="h-64 overflow-hidden">
                    @if ($circulars->count() > 0)
                    @foreach ($circulars as $circular)
                    <div class="circular-item flex items-center p-4 mb-3 bg-gray-50 rounded-lg shadow-sm">
                        {{-- Date --}}
                        <div class="flex-shrink-0 text-center mr-6">
                            <span class="block text-2xl font-bold text-indigo-600">
                                {{ \Carbon\Carbon::parse($circular->created_at)->format('d') }}
                            </span>
                            <span class="block text-sm font-medium text-gray-500">
                                {{ \Carbon\Carbon::parse($circular->created_at)->format('M') }}
                            </span>
                        </div>
                        {{-- Details --}}
                        <div class="flex flex-col px-6">
                            <p class="text-lg font-medium text-gray-900">
                                {{ ucfirst($circular->circular_name) ?? 'Untitled Circular' }}
                            </p>
                            {{-- Trigger Modal --}}
                            <td class="text-center">
                                <a href="#fileModal{{ $circular->id }}" data-bs-toggle="modal" data-bs-target="#fileModal{{ $circular->id }}" data-file="{{ asset('storage/' . $circular->file_path) }}">
                                    View
                                </a>
                            </td>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <p class="text-gray-500 text-center py-8">No circulars are available.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Modals for Circulars --}}
        @foreach ($circulars as $circular)
        <div class="modal fade" id="fileModal{{ $circular->id }}" tabindex="-1" aria-labelledby="fileModalLabel{{ $circular->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fileModalLabel{{ $circular->id }}">{{ $circular->circular_name ?? 'Untitled Circular' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Empty iframe initially --}}
                        <iframe id="modalIframe{{ $circular->id }}" src="" width="100%" height="600px" frameborder="0" oncontextmenu="return false;"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

{{-- LOADING THE MODEL --}}
<script>
    // Use JavaScript to lazy load the iframe content when the modal is shown
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners for each modal
        @foreach($circulars as $circular)
        var modal {
            {
                $circular - > id
            }
        } = document.getElementById('fileModal{{ $circular->id }}');
        modal {
            {
                $circular - > id
            }
        }.addEventListener('show.bs.modal', function() {
            var iframe = document.getElementById('modalIframe{{ $circular->id }}');
            var fileUrl = '{{ asset("storage/" . $circular->file_path) }}';
            iframe.src = 'https://docs.google.com/viewer?embedded=true&url=' + encodeURIComponent(fileUrl);
        });

        // Reset iframe content when modal is closed
        modal {
            {
                $circular - > id
            }
        }.addEventListener('hidden.bs.modal', function() {
            var iframe = document.getElementById('modalIframe{{ $circular->id }}');
            iframe.src = ''; // Reset iframe to avoid further loading
        });
        @endforeach
    });
</script>