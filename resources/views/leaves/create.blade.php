<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ isset($leave) ? 'Edit Leave' : 'Apply Leave' }}
            </h2>
            <a href="{{ route('leaves.index') }}" class="text-sm text-red-700 no-underline">&larr; Back</a>
        </div>
        <hr>


        <div class="py-6 w-50 ">
            {{-- Show general errors and session messages --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Whoops!</strong>
                    <span class="block sm:inline">There were some problems with your input.</span>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST"
                action="{{ isset($leave) ? route('leaves.update', $leave->id) : route('leaves.store') }}">
                @csrf
                @if (isset($leave))
                    @method('PUT')
                @endif

                {{-- Employee Info (Read-only) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="emp_id" class="block text-sm font-medium text-gray-700">Employee ID</label>
                        <input type="text" value="{{ auth()->user()->employees->emp_id ?? 'N/A' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm" readonly>
                        <input type="hidden" name="emp_id" value="{{ auth()->user()->employees->emp_id ?? '' }}">
                    </div>
                    <div>
                        <label for="emp_name" class="block text-sm font-medium text-gray-700">Employee Name</label>
                        <input type="text" value="{{ auth()->user()->name }}"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm" readonly>
                    </div>
                    <div>
                        <label for="emp_role" class="block text-sm font-medium text-gray-700">Designation</label>
                        <input type="text"
                            value="{{ auth()->user()->employees->designation->designation_name ?? 'N/A' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm" readonly>
                    </div>
                </div>

                {{-- Leave Type & Duration --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="leave_type" class="block text-sm font-medium text-gray-700">Leave Type</label>
                        <select name="leave_type" id="leave_type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Type</option>
                            <option value="casual"
                                {{ old('leave_type', $leave->leave_type ?? '') == 'casual' ? 'selected' : '' }}>Casual
                            </option>
                            <option value="sick"
                                {{ old('leave_type', $leave->leave_type ?? '') == 'sick' ? 'selected' : '' }}>Sick
                            </option>
                            <option value="earned"
                                {{ old('leave_type', $leave->leave_type ?? '') == 'earned' ? 'selected' : '' }}>Earned
                            </option>
                            <option value="comp-off"
                                {{ old('leave_type', $leave->leave_type ?? '') == 'comp-off' ? 'selected' : '' }}>
                                Comp-Off</option>
                        </select>
                    </div>
                    <div>
                        <label for="leave_duration" class="block text-sm font-medium text-gray-700">Duration</label>
                        <select name="leave_duration" id="leave_duration"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="Full Day"
                                {{ old('leave_duration', $leave->leave_duration ?? '') == 'Full Day' ? 'selected' : '' }}>
                                Full Day</option>
                            <option value="Half Day"
                                {{ old('leave_duration', $leave->leave_duration ?? '') == 'Half Day' ? 'selected' : '' }}>
                                Half Day</option>
                        </select>
                    </div>
                </div>

                {{-- Normal Leave Date Fields --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4" id="normal_date_fields">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" id="start_date"
                            value="{{ old('start_date', $leave->start_date ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('start_date')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date" id="end_date"
                            value="{{ old('end_date', $leave->end_date ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('end_date')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Comp-off Leave Date Fields --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4" id="comp_off_fields" style="display: none;">
                    <div>
                        <label for="comp_off_worked_date" class="block text-sm font-medium text-gray-700">Worked
                            Date</label>
                        <input type="date" name="comp_off_worked_date" id="comp_off_worked_date"
                            value="{{ old('comp_off_worked_date', $leave->comp_off_worked_date ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('comp_off_worked_date')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="comp_off_leave_date" class="block text-sm font-medium text-gray-700">Leave
                            Date</label>
                        <input type="date" name="comp_off_leave_date" id="comp_off_leave_date"
                            value="{{ old('comp_off_leave_date', $leave->comp_off_leave_date ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('comp_off_leave_date')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Other Fields --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="leave_days" class="block text-sm font-medium text-gray-700">No. of Leave
                            Days</label>
                        <input type="number" name="leave_days" id="leave_days" min="0" step="0.5"
                            value="{{ old('leave_days', $leave->leave_days ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm" readonly>
                        @error('leave_days')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Available Leaves</label>
                        <input type="text" value="{{ $availableLeaves ?? 1 }}"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm text-green-600 font-semibold"
                            readonly>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                    <textarea name="reason" id="reason" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('reason', $leave->reason ?? '') }}</textarea>
                    @error('reason')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
                {{-- Submit Button --}}
                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        {{ isset($leave) ? 'Update Leave' : 'Apply Leave' }}
                    </button>
                </div>
            </form>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const leaveTypeSelect = document.getElementById('leave_type');
                    const leaveDurationSelect = document.getElementById('leave_duration');
                    const startDateInput = document.getElementById('start_date');
                    const endDateInput = document.getElementById('end_date');
                    const compOffWorkedDateInput = document.getElementById('comp_off_worked_date');
                    const compOffLeaveDateInput = document.getElementById('comp_off_leave_date');
                    const leaveDaysInput = document.getElementById('leave_days');
                    const normalDateFields = document.getElementById('normal_date_fields');
                    const compOffFields = document.getElementById('comp_off_fields');

                    function calculateLeaveDays() {
                        const type = leaveTypeSelect.value;
                        const duration = leaveDurationSelect.value;
                        let days = 0;

                        if (type === 'comp-off') {
                            days = 1;
                        } else {
                            const from = new Date(startDateInput.value);
                            const to = new Date(endDateInput.value);
                            if (from && to && from <= to) {
                                const diffTime = Math.abs(to - from);
                                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                                days = duration === 'Half Day' ? 0.5 : diffDays;
                            }
                        }
                        leaveDaysInput.value = days > 0 ? days : '';
                    }

                    function toggleLeaveFields() {
                        const type = leaveTypeSelect.value;
                        if (type === 'comp-off') {
                            compOffFields.style.display = 'grid';
                            normalDateFields.style.display = 'none';
                            startDateInput.required = false;
                            endDateInput.required = false;
                            compOffWorkedDateInput.required = true;
                            compOffLeaveDateInput.required = true;
                            leaveDurationSelect.value = 'Full Day';
                            leaveDurationSelect.querySelector('option[value="Half Day"]').disabled = true;
                        } else {
                            compOffFields.style.display = 'none';
                            normalDateFields.style.display = 'grid';
                            startDateInput.required = true;
                            endDateInput.required = true;
                            compOffWorkedDateInput.required = false;
                            compOffLeaveDateInput.required = false;
                            leaveDurationSelect.querySelector('option[value="Half Day"]').disabled = false;
                        }
                        calculateLeaveDays();
                    }

                    leaveTypeSelect.addEventListener('change', toggleLeaveFields);
                    leaveDurationSelect.addEventListener('change', calculateLeaveDays);
                    startDateInput.addEventListener('change', calculateLeaveDays);
                    endDateInput.addEventListener('change', calculateLeaveDays);
                    compOffLeaveDateInput.addEventListener('change', calculateLeaveDays); // For comp-off logic

                    // Run on page load to set initial state
                    toggleLeaveFields();
                });
            </script>
        @endpush
    </x-slot>
</x-app-layout>
