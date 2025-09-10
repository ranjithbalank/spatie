<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Employees Details') }}
            </h2>

            <a href="#" class="text-sm text-red-700 no-underline"
                onclick="window.history.back(); return false;">&larr; Back</a>
        </div>
        <hr class="mb-4">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
            @can('create employees')
                <a href="{{ route('employees.create') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    + Create Employees
                </a>
            @endcan

            <form method="GET" action="{{ route('employees.index') }}" class="flex items-end gap-2 w-full sm:w-1/3">
                <input type="text" name="search" placeholder="Search Employees..." value="{{ request('search') }}"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 flex-1" />
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Search
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <ul role="list" class="divide-y divide-gray-200">
                        <li class="flex justify-between gap-x-6 bg-gray-50 py-3 px-4 font-semibold text-gray-900">
                            <span class="flex-1">Employee Details</span>
                            @canany(['edit employees', 'delete employees'])
                                <span class="w-24 text-right">Actions</span>
                            @endcanany
                        </li>
                        @forelse ($employees as $employee)
                            <li class="flex justify-between gap-x-6 py-4 px-4 hover:bg-gray-50">
                                <div class="flex-1 flex items-center gap-x-4">
                                    <div
                                        class="h-10 w-10 flex-none rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-semibold">
                                        {{ strtoupper(substr($employee->emp_name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ Str::ucfirst($employee->emp_name) }}
                                        </p>
                                        {{-- <p class="text-xs text-gray-500">
                                            Emp ID: {{ $employee->emp_id }} |
                                       <span class="text-xs text-gray-500">
                                            Designation: {{ $employee->designation_name ?? 'N/A' }}
                                       </span>
                                        <p class="text-xs text-gray-500">
                                            Manager:
                                            {{ $employee->manager ? $employee->manager->employee_name : 'N/A' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Status: {{ Str::ucfirst($employee->status) }}
                                        </p> --}}
                                    </div>
                                </div>
                                <div class="w-24 flex justify-end items-center gap-2">
                                    @can('edit employees')
                                        <a href="{{ route('employees.edit', $employee->id) }}"
                                            class="text-blue-600 hover:underline text-sm">Edit</a>
                                    @endcan
                                    @can('delete employees')
                                        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:underline text-sm">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            </li>
                        @empty
                            <li class="py-4 px-4 text-center text-gray-500">
                                No Employees found.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        @if ($employees instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $employees->links() }}
        @endif
    </x-slot>
</x-app-layout>
