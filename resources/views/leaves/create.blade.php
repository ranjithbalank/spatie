<x-app-layout>
    <x-slot name="header">
         <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Apply Leaves') }}
            </h2>
            <a href="#" class="text-sm text-red-700 no-underline"
                onclick="window.history.back(); return false;">&larr; Back</a>
        </div>
        <hr class="mb-4">
        {{-- This section correctly displays general session errors --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <div class="py-3 w-50">
            {{-- <div class="max-w-4xl mx-auto"> --}}
                {{-- <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"> --}}
                    <div class="text-gray-900">

                        <form method="POST" action="{{ route('leaves.store') }}">
                            @csrf

                            <!-- From Date -->
                            <div class="mb-4">
                                <label for="from_date" class="block text-sm font-medium text-gray-700">From Date</label>
                                <input type="date" name="from_date" id="from_date"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                {{-- ADD THIS SECTION --}}
                                @error('from_date')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- To Date -->
                            <div class="mb-4">
                                <label for="to_date" class="block text-sm font-medium text-gray-700">To Date</label>
                                <input type="date" name="to_date" id="to_date"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                {{-- ADD THIS SECTION --}}
                                @error('to_date')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Leave Days (Auto-calculated) -->
                            <div class="mb-4">
                                <label for="leave_days" class="block text-sm font-medium text-gray-700">Leave
                                    Days</label>
                                <input type="number" name="leave_days" id="leave_days"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" readonly>
                            </div>

                            <!-- Reason -->
                            <div class="mb-4">
                                <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                                <textarea name="reason" id="reason" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>

                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Submit
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ Script directly here so it always runs -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const fromDate = document.getElementById("from_date");
                const toDate = document.getElementById("to_date");
                const leaveDays = document.getElementById("leave_days");

                function calculateLeaveDays() {
                    if (fromDate.value && toDate.value) {
                        const from = new Date(fromDate.value);
                        const to = new Date(toDate.value);

                        if (!isNaN(from) && !isNaN(to) && from <= to) {
                            const days = Math.floor((to - from) / (1000 * 60 * 60 * 24)) + 1;
                            leaveDays.value = days;
                        } else {
                            leaveDays.value = "";
                        }
                    }
                }

                function setMinDates() {
                    const today = new Date();
                    // Calculate the date from 7 days ago
                    const sevenDaysAgo = new Date();
                    sevenDaysAgo.setDate(today.getDate() - 7);

                    // Format dates to "YYYY-MM-DD"
                    const minDate = sevenDaysAgo.toISOString().split("T")[0];
                    const maxDate = today.toISOString().split("T")[0];

                    // Assuming 'fromDate' and 'toDate' are your input elements
                    fromDate.setAttribute("min", minDate);
                    // fromDate.setAttribute("max", maxDate);

                    toDate.setAttribute("min", minDate);
                    // toDate.setAttribute("max", maxDate);
                }

                // fromDate and toDate event listeners and calculateLeaveDays function remain the same.
                fromDate.addEventListener("change", function() {
                    // The toDate's min attribute should still be dependent on the fromDate selection
                    toDate.min = fromDate.value;
                    calculateLeaveDays();
                });

                toDate.addEventListener("change", calculateLeaveDays);

                setMinDates();
            });
        </script>
    </x-slot>
</x-app-layout>
