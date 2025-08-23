<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ isset($leave) ? __('Edit Leave') : __('Apply Leave') }}
            </h2>
            <a href="{{ route('leaves.index') }}" class="text-sm text-red-700 no-underline">
                &larr; {{ __('Back') }}
            </a>
        </div>
        <hr>


        <div class="py-6 ">
            <form method="POST"
                action="{{ isset($leave) ? route('leaves.update', $leave->id) : route('leaves.store') }}">
                @csrf
                @if (isset($leave))
                    @method('PUT')
                @endif

                {{-- Employee Info --}}
                <div class="grid grid-cols-3 gap-4 mb-4 w-50">

                    <div>
                        <label for="emp_id" class="block text-sm font-medium text-gray-700">Employee ID</label>
                        <input type="text" name="emp_id" id="emp_id"
                            value="{{ auth()->user()->employees->first()->emp_id ?? '' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm" readonly>
                    </div>

                    <div>
                        <label for="emp_name" class="block text-sm font-medium text-gray-700">Employee Name</label>
                        <input type="text" id="emp_name"
                            value="{{ auth()->user()->employee->emp_name ?? auth()->user()->name }}"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm" readonly>
                    </div>

                    <div>
                        <label for="emp_role" class="block text-sm font-medium text-gray-700">Designation</label>
                        <input type="text" id="emp_role"
                            value="{{ auth()->user()->employees->designation->designation_name }}"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm" readonly>
                    </div>
                </div>

                {{-- Start & End Date --}}
                <div class="grid grid-cols-3 gap-4 w-50">
                    <div class="mb-4">
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" id="start_date"
                            value="{{ $leave->start_date ?? old('start_date') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('start_date')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date" id="end_date"
                            value="{{ $leave->end_date ?? old('end_date') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('end_date')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- Total Days --}}
                    <div class="mb-4">
                        <label for="total_days" class="block text-sm font-medium text-gray-700">Total Days</label>
                        <input type="number" name="total_days" id="total_days"
                            value="{{ $leave->total_days ?? old('total_days') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            readonly>
                        @error('total_days')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                @can('approve leaves')
                    {{-- Leave Type --}}
                    <div class="mb-4 w-25">
                        <label for="leave_type" class="block text-sm font-medium text-gray-700">Leave Type</label>
                        <select name="leave_type" id="leave_type"
                            class="mt-1 block w-1/2 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Leave Type</option>
                            @foreach (['sick', 'casual', 'paid', 'unpaid', 'maternity', 'other'] as $type)
                                <option value="{{ $type }}"
                                    {{ (isset($leave) && $leave->leave_type == $type) || old('leave_type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                        @error('leave_type')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                @endcan
                {{-- Reason --}}
                <div class="mb-4 ">
                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                    <textarea name="reason" id="reason" rows="3"
                        class="mt-1 block w-25 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ $leave->reason ?? old('reason') }}</textarea>
                    @error('reason')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Manager Remark (optional, only for edit) --}}
                @if (isset($leave))
                    <div class="mb-4">
                        <label for="manager_remark" class="block text-sm font-medium text-gray-700">Manager
                            Remark</label>
                        <textarea name="manager_remark" id="manager_remark" rows="2"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ $leave->manager_remark ?? old('manager_remark') }}</textarea>
                    </div>
                @endif

                {{-- Submit --}}
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        {{ isset($leave) ? 'Update Leave' : 'Apply Leave' }}
                    </button>
                </div>
            </form>
        </div>

        {{-- JS to auto-calculate total days --}}
        @push('scripts')
            <script>
                function calculateTotalDays() {
                    const start = document.getElementById('start_date').value;
                    const end = document.getElementById('end_date').value;
                    const totalDaysInput = document.getElementById('total_days');

                    if (start && end) {
                        const startDate = new Date(start);
                        const endDate = new Date(end);
                        const diffTime = endDate - startDate;
                        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;
                        totalDaysInput.value = diffDays > 0 ? diffDays : 0;
                    } else {
                        totalDaysInput.value = '';
                    }
                }

                document.getElementById('start_date').addEventListener('change', calculateTotalDays);
                document.getElementById('end_date').addEventListener('change', calculateTotalDays);

                window.addEventListener('load', calculateTotalDays);
            </script>
        @endpush
    </x-slot>
</x-app-layout>
