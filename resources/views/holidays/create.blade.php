<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Create Holidays') }}
            </h2>

            <a href="#" class="text-sm text-red-700 no-underline"
                onclick="window.history.back(); return false;">&larr; Back</a>
        </div>
        <hr class="mb-4">

        <div class="card-body w-25">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Please fix the following errors:<br><br>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('holidays.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Holiday Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter holiday name"
                        value="{{ old('name') }}" autocomplete="off">
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Holiday Date</label>
                    <input type="date" name="date" class="form-control" value="{{ old('date') }}">
                </div>

                {{-- Close form tags AFTER card-footer --}}
        </div>

        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary shadow-sm">Save</button>

        </div>

        </form> {{-- form ends here --}}

    </x-slot>
</x-app-layout>
