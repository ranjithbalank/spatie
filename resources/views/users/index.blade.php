<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Users') }}
            </h2>

            <a href="{{ route('dashboard') }}" class="text-sm text-red-700 no-underline">
                &larr; {{ __('Back') }}
            </a>
        </div>
        <hr class="mb-4">


        {{-- <div class="py-12"> --}}
            {{-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> --}}
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
                    <a href="{{ route('users.create') }}"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        + Create Users
                    </a>

                    <form method="GET" action="{{ route('users.index') }}"
                        class="flex items-end gap-2 w-full sm:w-1/3">
                        <input type="text" name="search" placeholder="Search Users..."
                            value="{{ request('search') }}"
                            class="px-2 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 flex-1" />

                        <select name="role_id"
                            class="px-3 py-2 border border-gray-300 w-full rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">All Roles</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Search
                        </button>
                    </form>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <ul role="list" class="divide-y divide-gray-200">
                            <li class="flex justify-between gap-x-6 bg-gray-50 py-3 px-4 font-semibold text-gray-900">
                                <span class="flex-1">User Name</span>
                                <span class="">Role(s) </span>
                                <span class="w-24 text-right">Actions</span>
                            </li>

                            @forelse ($users as $user)
                                <li class="flex justify-between gap-x-6 py-4 px-4 hover:bg-gray-50">
                                    <div class="flex-1 flex items-center gap-x-4">
                                        <div
                                            class="h-10 w-10 flex-none rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-semibold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-m font-medium text-red-900 truncate">
                                                {{ Str::ucfirst($user->name) }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                Employee id:<span class="text-red-600"> {{ $user->id }} </span>|
                                                Status:
                                                @if ($user->status === 'active')
                                                    <span
                                                        class="text-green-600 font-bold">{{ ucfirst($user->status) }}</span>
                                                @else
                                                    <span
                                                        class="text-red-600 font-bold">{{ ucfirst($user->status) }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1">
                                        @forelse ($user->roles as $role)
                                            <span
                                                class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @empty
                                            <span class="text-gray-500 text-sm">No Role</span>
                                        @endforelse
                                    </div>

                                    <div class="w-24 flex justify-end items-center gap-2">
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="text-blue-600 hover:underline text-sm">Edit</a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:underline text-sm">Delete</button>
                                        </form>
                                    </div>
                                </li>
                            @empty
                                <li class="py-4 px-4 text-center text-gray-500">
                                    No users found.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                @if ($users->hasPages())
                    <div class="mt-4">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

    </x-slot>
</x-app-layout>
