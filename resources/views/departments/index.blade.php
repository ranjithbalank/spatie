<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Departments') }}
            </h2>

            <a href="#" class="text-sm text-red-700 no-underline"
                onclick="window.history.back(); return false;">&larr; Back</a>
        </div>

        <hr class="mb-4">

        <!-- Top Controls: Create Button + Search -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
            @can('create departments')
                <a href="{{ route('departments.create') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    + Create Department
                </a>
            @endcan


            <form method="GET" action="{{ route('departments.index') }}" class="flex items-end gap-2 w-full sm:w-1/3">
                <input type="text" name="search" placeholder="Search Menu Items..." value="{{ request('search') }}"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 flex-1" />
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Search
                </button>
            </form>
        </div>

        <!-- Departments Table -->
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <ul role="list" class="divide-y divide-gray-200">
                        <!-- Header Row -->
                        <li class="flex justify-between gap-x-6 bg-gray-50 py-3 px-4 font-semibold text-gray-900">
                            <span class="flex-1">Department Name</span>
                            @canany(['edit departments', 'delete departments'])
                                <span class="w-24 text-right">Actions</span>
                            @endcanany(["edit departments" ,"delete departments"])

                        </li>

                        @forelse ($departments as $department)
                            <li class="flex justify-between gap-x-6 py-4 px-4 hover:bg-gray-50">
                                <div class="flex-1 flex items-center gap-x-4">
                                    <div
                                        class="h-10 w-10 flex-none rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-semibold">
                                        {{ strtoupper(substr($department->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ Str::ucfirst($department->name) }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Code: {{ $department->code }}
                                            @if ($department->unit)
                                                | Unit: {{ $department->unit->name }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="w-24 flex justify-end items-center gap-2">
                                    @can('edit departments')
                                        <a href="{{ route('departments.edit', $department->id) }}"
                                            class="text-blue-600 hover:underline text-sm">Edit</a>
                                    @endcan
                                    @can('delete departments')
                                        <form action="{{ route('departments.destroy', $department->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this department?');">
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
                                No Departments found.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $departments->links() }}
        </div>
    </x-slot>
</x-app-layout>
