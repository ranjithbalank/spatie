{{-- =============================================================================
    File       : create.blade.php
    Author     : Ranjithbalan K
    Module     : User Management
    Purpose    : Form to create or edit a user record
    Laravel Ver: 12.x
    Last Modified: 2025-09-17 by Ranjithbalan K
    Version    : v1.0.0

    Change History:
    -----------------------------------------------------------------------------
    Version | Date       | Author         | Description
    -----------------------------------------------------------------------------
    v1.0.0  | 2025-09-17 | Ranjithbalan K | Initial form creation with validation for create/edit
    -----------------------------------------------------------------------------

    Notes:
    - Includes role selection and status dropdown
    - Password fields are required only for new user creation
    - Shows error messages at top if validation fails
    - Reusable for both create and edit scenarios
============================================================================= --}}

<x-app-layout>
    <x-slot name="header">
        {{-- Page Header --}}
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ isset($user) ? __('Edit User') : __('Create User') }}
            </h2>
            <a href="{{ route('users.index') }}" class="text-sm text-red-700 no-underline">
                &larr; {{ __('Back') }}
            </a>
        </div>

        <hr class="mb-4">

        {{-- Display Validation Errors --}}
        @if ($errors->any())
        <div class="mb-4 text-red-600 p-4 bg-red-50 rounded-lg">
            <h3 class="font-bold mb-2">Whoops! Something went wrong.</h3>
            <ul>
                @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Form Container --}}
        <div class="p-6 max-w-2xl">
            <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST">
                @csrf
                @if(isset($user))
                @method('PUT')
                @endif

                <div class="space-y-6">
                    {{-- -----------------------------
                         First Row: Full Name & Email
                    ----------------------------- --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">Full Name *</label>
                            <input type="text" id="name" name="name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('name', $user->name ?? '') }}" required />
                        </div>

                        <div>
                            <label for="email" class="block font-medium text-sm text-gray-700">Email Address *</label>
                            <input type="email" id="email" name="email"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('email', $user->email ?? '') }}" required />
                        </div>
                    </div>

                    {{-- -----------------------------
                         Second Row: Role & Status
                    ----------------------------- --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="role" class="block font-medium text-sm text-gray-700">Role *</label>
                            <select id="role" name="role"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="">-- Select Role --</option>
                                @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block font-medium text-sm text-gray-700">Status</label>
                            <select id="status" name="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="active" {{ old('status', $user->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $user->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    {{-- -----------------------------
                         Third Row: Password Fields
                         Required only on Create
                    ----------------------------- --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                            <input type="password" id="password" name="password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                @if(!isset($user)) required @endif />
                        </div>
                        <div>
                            <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                @if(!isset($user)) required @endif />
                        </div>
                    </div>
                </div>

                {{-- -----------------------------
                     Form Actions: Cancel & Submit
                ----------------------------- --}}
                <div class="flex justify-start mt-6">
                    <a href="{{ route('users.index') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2 hover:bg-gray-600 transition duration-150 ease-in-out">Cancel</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-150 ease-in-out">
                        {{ isset($user) ? 'Update User' : 'Create User' }}
                    </button>
                </div>
            </form>
        </div>
    </x-slot>
</x-app-layout>