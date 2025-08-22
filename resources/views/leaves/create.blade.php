<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Leave Details') }}
            </h2>

            <a href="{{ route('dashboard') }}" class="text-sm text-red-700 no-underline">
                &larr; {{ __('Back') }}
            </a>
        </div>
        <hr class="mb-4">

        {{-- Leave View Tabs --}}
        <div class="flex flex-col sm:flex-row sm:justify-start gap-4 mb-4">
            <a href="{{ route('leaves.index', ['view' => 'mine']) }}"
               class="px-4 py-2 rounded-md transition {{ request()->get('view') !== 'team' ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-200' }}">
                My Leaves
            </a>

            @if (auth()->user()->hasAnyRole(['Manager', 'Admin', 'HR']))
                <a href="{{ route('leaves.index', ['view' => 'team']) }}"
                   class="px-4 py-2 rounded-md transition {{ request()->get('view') === 'team' ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-200' }}">
                   {{ auth()->user()->hasRole('Admin') ? 'All Leaves' : 'Leave Approvals' }}
                   @if (!empty($pendingCount) && $pendingCount > 0)
                       <span class="ml-1 px-2 py-1 bg-red-500 text-white text-xs rounded-full">
                           {{ $pendingCount }}
                       </span>
                   @endif
                </a>
            @endif
        </div>

        {{-- Action Buttons & Search --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
            @if (request()->get('view') !== 'team')
                <a href="{{ route('leaves.create') }}"
                   class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                   + Apply Leave
                </a>
            @else
                @hasanyrole('Admin|HR')
                    <div class="relative">
                        <button type="button"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition"
                            onclick="document.getElementById('exportMenu').classList.toggle('hidden')">
                            Export ▼
                        </button>
                        <ul id="exportMenu"
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg hidden">
                            <li>
                                <a href="{{ route('leaves.export.excel') }}"
                                   class="block px-4 py-2 text-green-600 hover:bg-gray-100">
                                   Download as Excel
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('leaves.export.pdf') }}"
                                   class="block px-4 py-2 text-red-600 hover:bg-gray-100">
                                   Download as PDF
                                </a>
                            </li>
                        </ul>
                    </div>
                @endhasanyrole
            @endif

            <form method="GET" action="{{ route('leaves.index') }}" class="flex items-end gap-2 w-full sm:w-1/3">
                <input type="text" name="search" placeholder="Search Leaves..."
                       value="{{ request('search') }}"
                       class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 flex-1" />
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Search
                </button>
            </form>
        </div>
    </x-slot>

    {{-- Leave Table --}}
    <div class="bg-white shadow rounded-lg p-4">
        @if ($leaves->isEmpty())
            <div class="text-center text-yellow-600 font-semibold">No leave records found.</div>
        @else
            <div class="overflow-x-auto">
                <table id="leaveTable" class="min-w-full border border-gray-200 divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">S.No</th>
                            @if (request()->get('view') === 'team')
                                <th class="px-4 py-2">Employee</th>
                            @endif
                            <th class="px-4 py-2">Leave</th>
                            <th class="px-4 py-2">From Date</th>
                            <th class="px-4 py-2">To Date</th>
                            <th class="px-4 py-2">Days</th>
                            <th class="px-4 py-2">Reason</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-center">
                        @foreach ($leaves as $index => $leave)
                            <tr>
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                @if (request()->get('view') === 'team')
                                    <td class="px-4 py-2 text-indigo-600">{{ Str::ucfirst($leave->user->name ?? '-') }}</td>
                                @endif
                                <td class="px-4 py-2 text-red-600 text-left">{{ $leave->leave_type }}</td>
                                <td class="px-4 py-2">
                                    {{ $leave->leave_type === 'comp-off' && $leave->comp_off_worked_date
                                        ? \Carbon\Carbon::parse($leave->comp_off_worked_date)->format('d M Y')
                                        : ($leave->from_date
                                            ? \Carbon\Carbon::parse($leave->from_date)->format('d M Y')
                                            : '-') }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $leave->leave_type === 'comp-off' && $leave->comp_off_leave_date
                                        ? \Carbon\Carbon::parse($leave->comp_off_leave_date)->format('d M Y')
                                        : ($leave->to_date
                                            ? \Carbon\Carbon::parse($leave->to_date)->format('d M Y')
                                            : '-') }}
                                </td>
                                <td class="px-4 py-2">{{ $leave->leave_days }}</td>
                                <td class="px-4 py-2">{{ ucfirst($leave->reason) }}</td>
                                <td class="px-4 py-2">
                                    @if ($leave->status == 'hr approved')
                                        <span class="px-2 py-1 bg-green-500 text-white rounded-md text-xs">HR APPROVED</span>
                                    @elseif ($leave->status == 'hr rejected')
                                        <span class="px-2 py-1 bg-red-500 text-white rounded-md text-xs">HR REJECTED</span>
                                    @elseif ($leave->status == 'supervisor/ manager approved')
                                        <span class="px-2 py-1 bg-blue-500 text-white rounded-md text-xs">SUPERVISOR APPROVED</span>
                                    @elseif ($leave->status == 'supervisor/ manager rejected')
                                        <span class="px-2 py-1 bg-red-500 text-white rounded-md text-xs">SUPERVISOR REJECTED</span>
                                    @elseif ($leave->status == 'pending')
                                        <span class="px-2 py-1 bg-yellow-400 text-black rounded-md text-xs">PENDING</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-400 text-white rounded-md text-xs">UNKNOWN</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex justify-center gap-2">
                                        @if (auth()->user()->hasRole('Employee') && auth()->id() === $leave->user_id)
                                            <a href="{{ route('leaves.edit', $leave->id) }}"
                                               class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs">
                                               Edit
                                            </a>
                                        @endif
                                        <button type="button"
                                                class="px-2 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600 text-xs"
                                                data-bs-toggle="modal" data-bs-target="#leaveModal{{ $leave->id }}">
                                            View
                                        </button>
                                        @if (auth()->user()->hasRole('Admin'))
                                            <form action="{{ route('leaves.destroy', $leave->id) }}" method="POST"
                                                  onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    @include('leaves.partials.show-modal', [
                                        'leave' => $leave,
                                        'user' => $leave->user ?? null,
                                    ])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- DataTables (optional, keep if you want sorting/pagination) --}}
    @push('scripts')
        <script src="https://cdn.datatables.net/2.3.2/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#leaveTable').DataTable({
                    "order": [],
                    "pageLength": 10
                });
            });
        </script>
    @endpush
</x-app-layout>
