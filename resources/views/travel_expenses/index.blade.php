<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Travel Expenses') }}
            </h2>
            <a href="#" class="text-sm text-red-700 no-underline hover:underline"
                onclick="window.history.back(); return false;">&larr; Back</a>
        </div>
        <hr class="my-4">
        <div class="container">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
                @can('create roles')
                <!-- Create Role Button -->
                <a href="{{ route('travel_expenses.create') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    + Create Expense request
                </a>
                @endcan
                <!-- Search bar -->
                <form method="GET" action="{{ route('roles.index') }}" class="flex items-end gap-2 w-full sm:w-1/3">
                    <input type="text" name="search" placeholder="Search Expenses ..." value="{{ request('search') }}"
                        class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 flex-1" />
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Search
                    </button>
                </form>
            </div>
            <!-- <h2>Travel Expenses</h2> -->
            <!-- <a href="{{ route('travel_expenses.create') }}" class="btn btn-primary mb-3">Create New Expense</a> -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Place of Visit</th>
                        <th>Purpose</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Claimed</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses_1 as $expense)
                    <tr>
                        <td>{{ $expense->place_of_visit }}</td>
                        <td>{{ $expense->purpose_of_visit }}</td>
                        <td>{{ $expense->start_date }}</td>
                        <td>{{ $expense->end_date }}</td>
                        <td>{{ $expense->total_expense_claimed }}</td>
                        <td>
                            <a href="{{ route('travel_expenses.show', $expense->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('travel_expenses.edit', $expense->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-slot>
</x-app-layout>