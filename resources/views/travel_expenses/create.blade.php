<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Travel Expense') }}
            </h2>
            <a href="{{ route('travel_expenses.index') }}" class="text-red-900 hover:underline"> &larr; Back</a>
        </div>
        <hr class="my-4">

        <form action="{{ isset($role) ? route('travel_expenses.save', $role->id) : route('travel_expenses.store') }}" method="POST"
            class="space-y-6 w-50" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <span class="text-lg font-semibold mb-4 text-red-600">Employee details Form</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee Id</label>
                    <input type="text" name="employee_id" id="employee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('employee_id', $user->Employees->id ?? '') }}" readonly required>
                </div>
                <div>
                    <label for="employee_name" class="block text-sm font-medium text-gray-700">Employee Name</label>
                    <input type="text" name="employee_name" id="employee_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('employee_name', $user->Employees->emp_name ?? '') }}" readonly required>
                </div>
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700">Unit</label>
                    <input type="text" name="unit" id="unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('unit', $user->Employees->unit->name?? '') }}" readonly required>
                </div>
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                    <input type="text" name="department" id="department" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('department', $user->Employees->department->name?? '') }}" readonly required>
                </div>
                <div>
                    <label for="designations" class="block text-sm font-medium text-gray-700">Designations</label>
                    <input type="text" name="designations" id="designations" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('designations', $user->Employees->designation->designation_name?? '') }}" readonly required>
                </div>
            </div>

            <div class="my-4">
                <span class="text-lg font-semibold mt-4 text-red-600">Travel Details Form (Section A)</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="place_of_visit" class="block text-sm font-medium text-gray-700">Place of Visit</label>
                    <input type="text" name="place_of_visit" id="place_of_visit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('place_of_visit', $expense->place_of_visit ?? '') }}" required>
                </div>
                <div>
                    <label for="purpose_of_visit" class="block text-sm font-medium text-gray-700">Purpose of Visit</label>
                    <input type="text" name="purpose_of_visit" id="purpose_of_visit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('purpose_of_visit', $expense->purpose_of_visit ?? '') }}" required>
                </div>
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('start_date', $expense->start_date ?? '') }}" required>
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('end_date', $expense->end_date ?? '') }}" required>
                </div>
                <div>
                    <label for="mode_of_travel" class="block text-sm font-medium text-gray-700">Mode of Travel</label>
                    <input type="text" name="mode_of_travel" id="mode_of_travel" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('mode_of_travel', $expense->mode_of_travel ?? '') }}" required>
                </div>
                <div>
                    <label for="kms" class="block text-sm font-medium text-gray-700">KMs</label>
                    <input type="text" name="kms" id="kms" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('kms', $expense->kms ?? '') }}" required>
                </div>
                <div>
                    <label for="pnr" class="block text-sm font-medium text-gray-700">PNR</label>
                    <input type="text" name="pnr" id="pnr" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('pnr', $expense->pnr ?? '') }}" required>
                </div>
                <div>
                    <label for="toll" class="block text-sm font-medium text-gray-700">Toll</label>
                    <input type="text" name="toll" id="toll" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('toll', $expense->toll ?? '') }}" required>
                </div>
                <div>
                    <label for="travel_receipt" class="block text-sm font-medium text-gray-700">Travel Bills Receipt (Upload)</label>
                    <input type="file" name="travel_receipt" id="travel_receipt" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="total_expense_claimed" class="block text-sm font-medium text-gray-700">Total Expense Claimed</label>
                    <input type="number" step="0.01" name="total_expense_claimed" id="total_expense_claimed" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('total_expense_claimed', $expense->total_expense_claimed ?? '') }}">
                </div>
            </div>

            <div class="my-4">
                <span class="text-lg font-semibold mt-4 text-red-600">Accommodation Details Form (Section B)</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="room" class="block text-sm font-medium text-gray-700">Room</label>
                    <input type="text" name="room" id="room" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('room', $expense->room ?? '') }}" required>
                </div>
                <div>
                    <label for="stay_date" class="block text-sm font-medium text-gray-700">Date of Stay</label>
                    <input type="date" name="stay_date" id="stay_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('stay_date', $expense->stay_date ?? '') }}" required>
                </div>
                <div>
                    <label for="stay_amount" class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" step="0.01" name="stay_amount" id="stay_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('stay_amount', $expense->stay_amount ?? '') }}" required>
                </div>
                <div>
                    <label for="stay_receipt" class="block text-sm font-medium text-gray-700">Stay Receipt (Upload)</label>
                    <input type="file" name="stay_receipt" id="stay_receipt" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>

            <div class="my-4">
                <span class="text-lg font-semibold mt-4 text-red-600">Local Conveyance Details Form (Section C)</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="conveyance_description" class="block text-sm font-medium text-gray-700">Description</label>
                    <input type="text" name="conveyance_description" id="conveyance_description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('conveyance_description', $expense->conveyance_description ?? '') }}" required>
                </div>
                <div>
                    <label for="conveyance_amount" class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" step="0.01" name="conveyance_amount" id="conveyance_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('conveyance_amount', $expense->conveyance_amount ?? '') }}" required>
                </div>
                <div>
                    <label for="mode_of_conveyance" class="block text-sm font-medium text-gray-700">Mode of Conveyance</label>
                    <input type="text" name="mode_of_conveyance" id="mode_of_conveyance" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('mode_of_conveyance', $expense->mode_of_conveyance ?? '') }}" required>
                </div>
            </div>

            <div class="my-4">
                <span class="text-lg font-semibold mt-4 text-red-600"> Any Other Expense (Section D)</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="other_expense_description" class="block text-sm font-medium text-gray-700">Description</label>
                    <input type="text" name="other_expense_description" id="other_expense_description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('other_expense_description', $expense->other_expense_description ?? '') }}" required>
                </div>
                <div>
                    <label for="other_expense_amount" class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" step="0.01" name="other_expense_amount" id="other_expense_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('other_expense_amount', $expense->other_expense_amount ?? '') }}" required>
                </div>
                <div>
                    <label for="other_mode_of_conveyance" class="block text-sm font-medium text-gray-700">Mode of Conveyance</label>
                    <input type="text" name="other_mode_of_conveyance" id="other_mode_of_conveyance" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('other_mode_of_conveyance', $expense->other_mode_of_conveyance ?? '') }}" required>
                </div>
                <div>
                    <label for="other_expense_receipt" class="block text-sm font-medium text-gray-700">Other Expense Receipt (Upload)</label>
                    <input type="file" name="other_expense_receipt" id="other_expense_receipt" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>


            <div class="mt-6">
                <div class="flex items-center mt-6">
                    <input class="form-check-input mb-3" type="checkbox" name="employee_signed" id="employee_signed" value="1"
                        {{ old('employee_signed', $expense->employee_signed ?? false) ? 'checked' : '' }}>
                    <label class="ml-2 text-sm text-gray-700 mb-3" for="employee_signed">
                        I confirm that the above details are accurate and submitted for approval.
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </x-slot>
</x-app-layout>