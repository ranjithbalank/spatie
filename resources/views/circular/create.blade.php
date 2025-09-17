<x-app-layout>
    {{-- ============================
        Create Circular Page
        Author   : Your Name
        Module   : Circular Management
        Purpose  : Form to upload and create new circular records
        Version  : 1.0.0
        History  :
            v1.0.0 - Initial form creation with validation, file upload
    ============================ --}}

    <x-slot name="header">
        {{-- Page Header --}}
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Create Circular') }}
            </h2>

            {{-- Back Button --}}
            <a href="#" class="text-sm text-red-700 no-underline"
                onclick="window.history.back(); return false;">&larr; Back</a>
        </div>

        <hr class="mb-4">

        <div class="col-md-12 w-75">

            {{-- Success Message Alert --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- Error Message Alert --}}
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- ============================
                Circular Create Form
                - POSTs to circulars.store route
                - Handles number, name, date, created_by, and file upload
            ============================ --}}
            <form action="{{ route('circulars.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row w-full">

                    {{-- Circular Number --}}
                    <div class="col-md-4 mb-3">
                        <label for="circular_number" class="form-label">Circular Number</label>
                        <input type="text" name="circular_number" id="circular_number"
                            class="form-control @error('circular_number') is-invalid @enderror"
                            value="{{ old('circular_number') }}" required>
                        @error('circular_number')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Circular Name --}}
                    <div class="col-md-4 mb-3">
                        <label for="circular_name" class="form-label">Circular Name</label>
                        <input type="text" name="circular_name" id="circular_name"
                            class="form-control @error('circular_number') is-invalid @enderror"
                            value="{{ old('circular_name') }}" required>
                        @error('circular_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Date of Circular --}}
                    <div class="col-md-4 mb-3">
                        <label for="circular_date" class="form-label">Date of Circular</label>
                        <input type="date" name="circular_date" id="circular_date"
                            class="form-control @error('circular_date') is-invalid @enderror"
                            value="{{ old('circular_date', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                        @error('circular_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Created By (auto-filled with logged-in user) --}}
                    <div class="col-md-4 mb-3">
                        <label for="created_by" class="form-label">Created By</label>
                        <input type="text" name="created_by" id="created_by" class="form-control"
                            value="{{ Auth::user()->name }}" readonly>
                    </div>

                    {{-- Upload Circular File (PDF only) --}}
                    <div class="col-md-4 mb-3">
                        <label for="circular_file" class="form-label">Circular File</label>
                        <input type="file" name="circular_file" id="circular_file"
                            class="form-control w-50 @error('circular_file') is-invalid @enderror"
                            accept=".pdf" style="height:50%">
                        @error('circular_file')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload"></i> Upload Circular
                    </button>
                </div>
            </form>
        </div>
    </x-slot>
</x-app-layout>