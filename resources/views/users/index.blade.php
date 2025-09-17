{{-- =============================================================================
    File       : index.blade.php
    Author     : Ranjithbalan K
    Module     : User Management
    Purpose    : Display list of users with search, role filter, and actions (edit/delete)
    Laravel Ver: 12.x
    Last Modified: 2025-09-17 by Ranjithbalan K
    Version    : v1.0.0

    Change History:
    -----------------------------------------------------------------------------
    Version | Date       | Author         | Description
    -----------------------------------------------------------------------------
    v1.0.0  | 2025-09-17 | Ranjithbalan K | Initial creation with user list, search, role filter, actions, and pagination
    -----------------------------------------------------------------------------

    Notes:
    - Users list displayed in a responsive flex-based layout
    - Search by name/email and filter by role
    - Actions (Edit/Delete) visible based on permissions
    - Displays user status, employee ID, and roles
============================================================================= --}}

<x-app-layout>
    <x-slot name="header">
        {{-- Page Header --}}
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black leading-tight">
                {{ __('Users') }}
            </h2>

            {{-- Back Button --}}
            <a href="{{ route('dashboard') }}" class="text-sm text-red-700 hover:underline">
                &larr; {{ __('Back') }}
            </a>
        </div>

        <hr class="mb-4">

        {{-- Top Actions: Create & Search --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
            @can('create users')
            <a href="{{ route('users.create') }}"
                class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                Create
            </a>
            @endcan

            {{-- Search and Role Filter Form --}}
            <form method="GET" action="{{ route('users.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
                <input type="text" name="search" placeholder="Search Users..." value="{{ request('search') }}"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 flex-1" />

                <select name="role_id"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Roles</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                    Search
                </button>
            </form>
        </div>

        {{-- Users List --}}
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            <ul role="list" class="divide-y divide-gray-200">
                {{-- Table Header --}}
                <li class="flex justify-between gap-x-6 bg-gray-100 py-3 px-4 font-semibold text-gray-900">
                    <span class="flex-1">User</span>
                    <span class="w-40">Roles</span>
                    @canany(['edit users', 'delete users'])
                    <span class="w-32 text-right">Actions</span>
                    @endcanany
                </li>

                {{-- User Rows --}}
                @forelse ($users as $user)
                <li class="flex justify-between items-center gap-x-6 py-4 px-4 hover:bg-gray-50 transition">
                    {{-- Left: Avatar + User Details --}}
                    <div class="flex-1 flex items-center gap-x-4">
                        <div
                            class="h-10 w-10 flex-none rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-semibold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-base font-medium text-gray-900">
                                {{ Str::ucfirst($user->name) }}
                            </p>
                            <p class="text-sm text-gray-500">
                                @foreach ($user->employees ?? [] as $emp)
                                @if (optional($emp)->emp_id)
                                <span class="text-gray-700">Employee ID:</span>
                                <span class="text-red-600">{{ $emp->emp_id }}</span> |
                                @endif
                                @endforeach
                                <span class="text-gray-700">Status:</span>
                                @if ($user->status === 'active')
                                <span class="text-green-600 font-semibold">{{ ucfirst($user->status) }}</span>
                                @else
                                <span class="text-red-600 font-semibold">{{ ucfirst($user->status) }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Middle: Roles --}}
                    <div class="w-40 flex flex-wrap gap-1">
                        @forelse ($user->roles as $role)
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                            {{ ucfirst($role->name) }}
                        </span>
                        @empty
                        <span class="text-gray-500 text-sm">No Role</span>
                        @endforelse
                    </div>

                    {{-- Right: Actions --}}
                    <div class="w-32 flex justify-end items-center gap-2">
                        @can('edit users')
                        <a href="{{ route('users.edit', $user->id) }}"
                            class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs rounded-lg hover:bg-indigo-200">
                            Edit
                        </a>
                        @endcan
                        @can('delete users')
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-lg hover:bg-red-200">
                                Delete
                            </button>
                        </form>
                        @endcan
                    </div>
                </li>
                @empty
                <li class="py-6 px-4 text-center text-gray-500">
                    No users found.
                </li>
                @endforelse
            </ul>
        </div>

        {{-- Pagination --}}
        @if ($users->hasPages())
        <div class="mt-4">
            {{ $users->appends(request()->query())->links() }}
        </div>
        @endif
    </x-slot>
</x-app-layout>