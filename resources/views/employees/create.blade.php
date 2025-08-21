<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ isset($employee) ? __('Edit Employee Details') : __('Create Employees Details') }}
            </h2>

            <a href="{{ route('dashboard') }}" class="text-sm text-red-700 no-underline">
                &larr; {{ __('Back') }}
            </a>
        </div>
        <hr class="mb-4">



        {{-- Flash message --}}
        @if (session('success'))
            <div class="bg-green-50 text-green-800 px-4 py-3 rounded border border-green-200 mb-6">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="bg-red-50 text-red-800 px-4 py-3 rounded border border-red-200 mb-6">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @php
            // $isEdit = isset($employee);
        @endphp
        {{-- Form --}}
        <form method="POST"
            action="{{ isset($employee) ? route('employees.update', $employee) : route('employees.store') }}"
            class="space-y-6 ">
            @csrf
            @if (isset($employee))
                @method('PUT')
            @endif

            {{-- Emp ID --}}
            <div class="w-50">
                <label class="block text-sm font-medium text-gray-700">
                    Employee ID <span class="text-red-500">*</span>
                </label>
                <input type="text" name="emp_id" value="{{ old('emp_id', $employee->emp_id ?? '') }}"
                    class="mt-1 block w-25 md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border"
                    required>
                @error('emp_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Employee Name --}}
            <div class="w-50">
                <label class="block text-sm font-medium text-gray-700">
                    Employee Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="employee_name"
                    value="{{ old('employee_name', $employee->employee_name ?? '') }}"
                    class="mt-1 block w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border"
                    required>
                @error('employee_name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Manager --}}
            <div class="w-50">
                <label class="block text-sm font-medium text-gray-700">Manager</label>
                <select name="manager_id"
                    class="mt-1 block w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                    <option value=""> Select Manager </option>
                    @foreach ($employees as $m)
                        <option value="{{ $m->id }}"
                            {{ (string) old('manager_id', $employee->manager_id ?? '') === (string) $m->id ? 'selected' : '' }}>
                            {{ $m->employee_name }} ({{ $m->emp_id }})
                        </option>
                    @endforeach
                </select>
                @error('manager_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Unit / Department / Designation --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-75">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Unit</label>
                    <select name="unit_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                        <option value="">Select Unit </option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}"
                                {{ (string) old('unit_id', $employee->unit_id ?? '') === (string) $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }} ({{ $unit->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Department</label>
                    <select name="department_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                        <option value="">Select Department</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}"
                                {{ (string) old('department_id', $employee->department_id ?? '') === (string) $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }} - ({{ $dept->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Designation</label>
                    <select name="designation_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                        <option value=""> Select Designation</option>
                        @foreach ($designations as $desig)
                            <option value="{{ $desig->id }}"
                                {{ (string) old('designation_id', $employee->designation_id ?? '') === (string) $desig->id ? 'selected' : '' }}>
                                {{ $desig->designation_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('designation_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Date of Joining and Relieving --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-25">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date of Joining</label>
                    <input type="date" name="doj"
                        value="{{ old('doj', isset($employee->doj) ? ($employee->doj instanceof \Illuminate\Support\Carbon ? $employee->doj->format('Y-m-d') : \Illuminate\Support\Str::of($employee->doj)->substr(0, 10)) : '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                    @error('doj')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date of Relieving</label>
                    <input type="date" name="dor"
                        value="{{ old('dor', isset($employee->dor) ? ($employee->dor instanceof \Illuminate\Support\Carbon ? $employee->dor->format('Y-m-d') : \Illuminate\Support\Str::of($employee->dor)->substr(0, 10)) : '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                    @error('dor')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Leave + Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-25">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Leave Balance</label>
                    <input type="number" min="0" name="leave_balance"
                        value="{{ old('leave_balance', $employee->leave_balance ?? 20) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                    @error('leave_balance')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    @php $status = old('status', $employee->status ?? 'active'); @endphp
                    <select name="status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="pt-5">
                <div class="flex justify-end">
                    <button type="submit"
                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ isset($employee) ? 'Update' : 'Create' }} Employee
                    </button>
                </div>
            </div>
        </form>

    </x-slot>
</x-app-layout>
