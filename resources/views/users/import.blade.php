{{-- @extends('layouts.app') <!-- remove this if you don’t use a layout -->

@section('content') --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Bulk update of  Users') }}
            </h2>

            <a href="{{ route('dashboard') }}" class="text-sm text-red-700 no-underline">
                &larr; {{ __('Back') }}
            </a>
        </div>
        <hr class="mb-4">
        @hasrole('admin')
            {{-- Allow HR or Admin --}}
            {{-- <div class="container"> --}}
                <h4 class="mb-3 fw-bold">Import Employees from Excel</h4>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        {{-- <label class="form-label">Choose Excel file (.xlsx or .csv)</label> --}}
                        <br>
                        <input type="file" name="file" class="form-control w-25 h-50" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Import Employees</button>
                </form>
            {{-- </div> --}}
        @else
            <div class="text-center mt-5">
                <img src="https://img.icons8.com/emoji/96/warning-emoji.png" alt="Warning" width="100" class="mb-3">
                <div class="alert alert-danger">
                    🚫 You are not authorized to access this page.
                </div>
            </div>
        @endhasrole
        {{-- @endsection --}}
    </x-slot>
</x-app-layout>
