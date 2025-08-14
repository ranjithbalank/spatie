<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Create Menu') }}
            </h2>

            <a href="{{ route('dashboard') }}" class="text-sm text-red-700 no-underline">
                &larr; {{ __('Back') }}
            </a>
        </div>

        <hr class="mb-4">
        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('menus.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700" >Name</label>
                <input type="text" name="name" class="form-input w-full rounded border-gray-300 w-25"
                    value="{{ old('name') }}" required>
            </div>

            {{-- <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700">Icon (optional)</label>
                <input type="text" name="icon" class="form-input w-full rounded border-gray-300 w-25"
                    value="{{ old('icon') }}" placeholder="fa fa-users">
            </div> --}}

            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700">URL</label>
                <input type="text" name="url" class="form-input w-full rounded border-gray-300 w-25"
                    value="{{ old('url') }}">
            </div>

            {{-- <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700">Parent Menu</label>
                <select name="parent_id" class="form-select w-full rounded border-gray-300 w-25">
                    <option value="">-- None --</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700">Order</label>
                <input type="number" name="order" class="form-input w-full rounded border-gray-300 w-25"
                    value="{{ old('order', 0) }}">
            </div> --}}

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="form-checkbox">
                    <span class="ml-2">Active</span>
                </label>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('menus.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded mr-2">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
            </div>
        </form>

    </x-slot>
</x-app-layout>
