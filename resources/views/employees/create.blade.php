<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ isset($employee) ? __('Edit Employee Details') : __('Create Employee Details') }}
            </h2>
            <a href="#" class="text-sm text-red-700 no-underline"
                onclick="window.history.back(); return false;">&larr; Back</a>
        </div>
        <hr class="mb-4">

        {{-- Flash message for success --}}
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

        {{-- Form for creating/editing an employee --}}
        <form method="POST"
            action="{{ isset($employee) ? route('employees.update', $employee) : route('employees.store') }}"
            class="space-y-6">
            @csrf
            @if (isset($employee))
                @method('PUT')
            @endif

            {{-- Hidden input for user ID --}}
            <div>
                <input type="text" name="user_id" value="{{ old('user_id', $employee->user_id ?? '') }}"
                    class="mt-1 block w-25 md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border"
                    hidden>
            </div>

            {{-- Employee ID --}}
            <div class="w-50">
                <label for="emp_id" class="block text-sm font-medium text-gray-700">
                    Employee ID <span class="text-red-500">*</span>
                </label>
                <input type="text" id="emp_id" name="emp_id" value="{{ old('emp_id', $employee->emp_id ?? '') }}"
                    class="mt-1 block w-25 md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border"
                    required>
                @error('emp_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Employee Name --}}
            <div class="w-50">
                <label for="emp_name" class="block text-sm font-medium text-gray-700">
                    Employee Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="emp_name" name="emp_name"
                    value="{{ old('emp_name', $employee->emp_name ?? '') }}"
                    class="mt-1 block w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border"
                    required>
                @error('emp_name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 w-75">
                {{-- Manager --}}
                <div>
                    <label for="manager_id" class="block text-sm font-medium text-gray-700 mb-1">Manager</label>
                    <select name="manager_id" id="manager_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border h-10 ">
                        <option value="">Select Manager</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->emp_id }}"
                                {{ (string) old('manager_id', $employee->manager_id ?? '') === (string) $emp->emp_id ? 'selected' : '' }}>
                                {{ $emp->emp_name }} ({{ $emp->emp_id }})
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email"
                        value="{{ old('email', $employee->user->email ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border"
                        required>
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                    <input type="password" id="password" name="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        @if (!isset($employee)) required @endif />
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirm
                        Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        @if (!isset($employee)) required @endif />
                </div>
            </div>

            {{-- Unit / Department / Designation --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-75">
                {{-- Unit --}}
                <div>
                    <label for="unit_id" class="block text-sm font-medium text-gray-700">Unit</label>
                    <select name="unit_id" id="unit_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                        <option value="">Select Unit</option>
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

                {{-- Department --}}
                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                    <select name="department_id" id="department_id"
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

                {{-- Designation --}}
                <div>
                    <label for="designation_id" class="block text-sm font-medium text-gray-700">Designation</label>
                    <select name="designation_id" id="designation_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                        <option value="">Select Designation</option>
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-75">
                <div>
                    <label for="doj" class="block text-sm font-medium text-gray-700">Date of Joining</label>
                    <input type="date" id="doj" name="doj"
                        value="{{ old('doj', isset($employee->doj) ? ($employee->doj instanceof \Illuminate\Support\Carbon ? $employee->doj->format('Y-m-d') : \Illuminate\Support\Str::of($employee->doj)->substr(0, 10)) : '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                    @error('doj')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="dor" class="block text-sm font-medium text-gray-700">Date of Relieving</label>
                    <input type="date" id="dor" name="dor"
                        value="{{ old('dor', isset($employee->dor) ? ($employee->dor instanceof \Illuminate\Support\Carbon ? $employee->dor->format('Y-m-d') : \Illuminate\Support\Str::of($employee->dor)->substr(0, 10)) : '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                    @error('dor')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="dob" class="block text-sm font-medium text-gray-700">
                        Date of Birth <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="dob" name="dob"
                        value="{{ old('dob', isset($employee->dob) ? ($employee->dob instanceof \Illuminate\Support\Carbon ? $employee->dob->format('Y-m-d') : \Illuminate\Support\Str::of($employee->dob)->substr(0, 10)) : '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border"
                        required>
                    @error('dob')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            {{-- @dd($users); --}}
            {{-- Leave + Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-25">
                <div>
                    <label for="leave_balance" class="block text-sm font-medium text-gray-700">Leave Balance</label>
                    <input type="number" min="0" id="leave_balance" name="leave_balance"
                        value="{{ old('leave_balance', $user->leave_balance ?? 20) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                    @error('leave_balance')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    @php $status = old('status', $employee->status ?? 'active'); @endphp
                    <select name="status" id="status"
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
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#manager_id').select2();
                $('#unit_id').select2();
                $('#department_id').select2();
                $('#designation_id').select2();
            });
        </script>
    </x-slot>
</x-app-layout>
