{{-- =============================================================================
    File       : bulk_import_users.blade.php
    Author     : Ranjithbalan K
    Module     : User Management
    Purpose    : Form to bulk import users via Excel/CSV file
    Laravel Ver: 12.x
    Last Modified: 2025-09-17 by Ranjithbalan K
    Version    : v1.0.0

    Change History:
    -----------------------------------------------------------------------------
    Version | Date       | Author         | Description
    -----------------------------------------------------------------------------
    v1.0.0  | 2025-09-17 | Ranjithbalan K | Initial creation with Excel import functionality
    -----------------------------------------------------------------------------

    Notes:
    - Only accessible to users with 'admin' role
    - Supports Excel (.xlsx) and CSV file uploads
    - Displays flash messages for success and error
    - Unauthorized users see a warning message
============================================================================= --}}

<x-app-layout>
    <x-slot name="header">
        {{-- Page Header --}}
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Bulk update of Users') }}
            </h2>

            {{-- Back Button --}}
            <a href="{{ route('dashboard') }}" class="text-sm text-red-700 no-underline">
                &larr; {{ __('Back') }}
            </a>
        </div>

        <hr class="mb-4">

        @hasrole('admin')
        {{-- ============================
                 Admin Only Section: Import Employees
            ============================ --}}
        <h4 class="mb-3 fw-bold">Import Employees from Excel</h4>

        {{-- Success Message --}}
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Error Message --}}
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Excel Import Form --}}
        <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <br>
                <input type="file" name="file" class="form-control w-25 h-50" required>
            </div>
            <button type="submit" class="btn btn-primary">Import Employees</button>
        </form>
        @else
        {{-- ============================
                 Unauthorized Access Warning
            ============================ --}}
        <div class="text-center mt-5">
            <img src="https://img.icons8.com/emoji/96/warning-emoji.png" alt="Warning" width="100" class="mb-3">
            <div class="alert alert-danger">
                🚫 You are not authorized to access this page.
            </div>
        </div>
        @endhasrole
    </x-slot>
</x-app-layout>