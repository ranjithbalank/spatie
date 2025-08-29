<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leave Application') }}
        </h2>


        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <form method="POST" action="{{ route('leaves.store') }}">
                            @csrf

                            <!-- From Date -->
                            <div class="mb-4">
                                <label for="from_date" class="block text-sm font-medium text-gray-700">From Date</label>
                                <input type="date" name="from_date" id="from_date"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <!-- To Date -->
                            <div class="mb-4">
                                <label for="to_date" class="block text-sm font-medium text-gray-700">To Date</label>
                                <input type="date" name="to_date" id="to_date"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
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
                    const minDate = today.toISOString().split("T")[0];
                    fromDate.setAttribute("min", minDate);
                    toDate.setAttribute("min", minDate);
                }

                fromDate.addEventListener("change", function() {
                    toDate.min = fromDate.value;
                    calculateLeaveDays();
                });

                toDate.addEventListener("change", calculateLeaveDays);

                setMinDates();
            });
        </script>
    </x-slot>
</x-app-layout>
