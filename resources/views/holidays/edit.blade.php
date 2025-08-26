<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Edit Holidays') }}
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

            <form action="{{ route('holidays.update', $holiday->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Holiday Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $holiday->name) }}">
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Holiday Date</label>
                    <input type="date" name="date" class="form-control" value="{{ old('date', $holiday->date) }}">
                </div>
                <br>
                <button type="submit" class="btn btn-primary shadow-sm">Update</button>
                <a href="{{ route('holidays.index') }}" class="btn btn-secondary shadow-sm">Cancel</a>
            </form>
        </div>

    </x-slot>
</x-app-layout>
