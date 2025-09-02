<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Create Circular') }}
            </h2>
            <a href="#" class="text-sm text-red-700 no-underline"
                onclick="window.history.back(); return false;">&larr; Back</a>
        </div>
        <hr class="mb-4">
        <div class="col-md-12 w-75">

            {{-- Success Message --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Error Message --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('circulars.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- <div class="card-body"> --}}
                <div class="row w-full">
                    {{-- Circular Number --}}
                    <div class="col-md-4 mb-3">
                        <label for="circular_number" class="form-label">Circular Number / Name</label>
                        <input type="text" name="circular_number" id="circular_number"
                            class="form-control @error('circular_number') is-invalid @enderror"
                            value="{{ old('circular_number') }}" required>
                        @error('circular_number')
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

                    {{-- Created By --}}
                    <div class="col-md-4 mb-3">
                        <label for="created_by" class="form-label">Created By</label>
                        <input type="text" name="created_by" id="created_by" class="form-control"
                            value="{{ Auth::user()->name }}" readonly>
                    </div>


                    {{-- Upload Circular File --}}
                    <div class="col-md-4 mb-3">
                        <label for="circular_file" class="form-label">Circular File</label>
                        <input type="file" name="circular_file" id="circular_file"
                            class="form-control w-50 @error('circular_file') is-invalid @enderror" accept=".pdf"
                            style="height:50%">
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
