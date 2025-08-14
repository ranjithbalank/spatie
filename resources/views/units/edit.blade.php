<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ isset($unit) ? __('Edit Unit') : __('Create Unit') }}
            </h2>

            <a href="{{ route('units.index') }}" class="text-sm text-red-700 no-underline">
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

        <form
            action="{{ isset($unit) ? route('units.update', $unit->id) : route('units.store') }}"
            method="POST"
        >
            @csrf
            @if(isset($unit))
                @method('PUT')
            @endif

            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700">Code</label>
                <input
                    type="text"
                    name="code"
                    class="form-input w-full rounded border-gray-300 w-25"
                    value="{{ old('code', $unit->code ?? '') }}"
                    required
                >
            </div>

            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700">Unit Name</label>
                <input
                    type="text"
                    name="name"
                    class="form-input w-full rounded border-gray-300 w-25"
                    value="{{ old('name', $unit->name ?? '') }}"
                    required
                >
            </div>

            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700">Unit Status</label>
                <select
                    name="status"
                    class="form-select w-full rounded border-gray-300 w-25"
                    required
                >
                    <option value="active" {{ old('status', $unit->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $unit->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('units.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded mr-2">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                    {{ isset($unit) ? 'Update' : 'Save' }}
                </button>
            </div>
        </form>
    </x-slot>
</x-app-layout>
