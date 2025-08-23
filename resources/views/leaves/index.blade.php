<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Leaves Details') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm text-red-700 no-underline">
                &larr; {{ __('Back') }}
            </a>
        </div>
        <hr>

        <!-- Tabs -->
        <div class="my-6" x-data="{ tab: 'applied' }">
            <div class="flex space-x-2 border- mb-4">
                <button @click="tab = 'applied'"
                    :class="tab === 'applied' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-600'"
                    class="pb-2 px-4 font-semibold">
                    My Leaves
                </button>
                <button @click="tab = 'approval'"
                    :class="tab === 'approval' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-600'"
                    class="pb-2 px-4 font-semibold">
                    Approval Queue
                </button>
            </div>

            <!-- Applied Leaves -->
            <div x-show="tab === 'applied'" class="overflow-x-auto">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
                    @can('create leaves')
                        <a href="{{ route('leaves.create') }}"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                            + Apply Leaves
                        </a>
                    @endcan
                    <form method="GET" action="{{ route('leaves.index') }}"
                        class="flex items-end gap-2 w-full sm:w-1/3">
                        <input type="text" name="search" placeholder="Search Leaves..."
                            value="{{ request('search') }}"
                            class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 flex-1" />
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Search
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    @if ($appliedLeaves->isEmpty())
                        <p class="text-gray-500 text-center m-4">No leave applications yet.</p>
                    @else
                        <table class="min-w-full border border-gray-200 divide-y divide-gray-200 text-center">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">#</th>
                                    <th class="px-4 py-2 text-left">Leave Type</th>
                                    <th class="px-4 py-2">From</th>
                                    <th class="px-4 py-2">To</th>
                                    <th class="px-4 py-2">Manager Status</th>
                                    <th class="px-4 py-2">HR Status</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($appliedLeaves as $index => $leave)
                                    <tr>
                                        <td class="px-4 py-2 text-left">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2 text-left">{{ $leave->leave_type ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">{{ $leave->start_date }}</td>
                                        <td class="px-4 py-2">{{ $leave->end_date }}</td>
                                        <td class="px-4 py-2">{{ ucfirst($leave->manager_status) }}</td>
                                        <td class="px-4 py-2">{{ ucfirst($leave->hr_status) }}</td>
                                        <td class="px-4 py-2 flex space-x-2"">
                                            <a href="{{ route('leaves.show', $leave->id) }}"
                                                class="px-3 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                                View
                                            </a>
                                            {{-- Delete Button --}}
                                            <form action="{{ route('leaves.destroy', $leave->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this leave request?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Approval Queue -->
            <div x-show="tab === 'approval'" class="overflow-x-auto mt-4">
                @if ($approvalLeaves->isEmpty())
                    <p class="text-gray-500 text-center">No leaves pending approval.</p>
                @else
                    <table class="min-w-full border border-gray-200 divide-y divide-gray-200 text-center">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2">#</th>
                                <th class="px-4 py-2 text-left">Employee</th>
                                <th class="px-4 py-2">Leave Type</th>
                                <th class="px-4 py-2">From</th>
                                <th class="px-4 py-2">To</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($approvalLeaves as $index => $leave)
                                <tr>
                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 text-left">{{ $leave->employee->emp_name ?? 'Employee' }}</td>
                                    <td class="px-4 py-2">{{ $leave->leave_type ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $leave->start_date }}</td>
                                    <td class="px-4 py-2">{{ $leave->end_date }}</td>
                                    <td class="px-4 py-2 flex gap-2 justify-center">
                                        <form method="POST" action="{{ route('leaves.approve', $leave->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700">
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('leaves.reject', $leave->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700">
                                                Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </x-slot>
</x-app-layout>
